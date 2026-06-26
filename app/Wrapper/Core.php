<?php

namespace Vpn\App\Wrapper;

use RuntimeException;

/*
 * VPN - Server-side software for centralized administration and node management of a VPN service.
 * Copyright (C) 2025 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

class Core extends \Vpn\App\Wrapper\System
{

    /**
     * Construct
     * @param string $endpoint
     * @param int $port
     */
    public function __construct(string $endpoint, int $port = 50051)
    {
        parent::__construct(
            $endpoint,
            $port,
            \Proto\Wireguard\WireguardServiceClient::class
        );
    }

    /**
     * Verify if it the port is available
     * @param string $host
     * @param int $port
     * @return bool
     */
    protected function isWireguardPortReachable(string $host, int $port): bool
    {
        // TCP probe
        $tcp = @fsockopen(
            $host,
            $port,
            $errno,
            $errstr,
            2
        );

        if ($tcp !== false) {
            fclose($tcp);
            return true;
        }

        // UDP probe (best effort)
        $udp = @stream_socket_client(
            "udp://{$host}:{$port}",
            $errno,
            $errstr,
            2
        );

        if ($udp === false) {
            return false;
        }

        stream_set_timeout($udp, 2);

        fwrite($udp, random_bytes(4));

        $meta = stream_get_meta_data($udp);

        fclose($udp);

        /*
         If timeout = probably reachable
         If immediate error = unreachable
        */

        return !($meta['timed_out'] ?? false);
    }

    /**
     * Mount wireguard interface
     * @param mixed $interface_name
     * @param mixed $subnet
     * @param mixed $gateway
     * @param mixed $private_key
     * @param mixed $physical_interface
     * @param mixed $listen_port
     * @param mixed $mtu
     * @throws RuntimeException
     */
    public function mountInterface(
        $interface_name,
        $subnet,
        $gateway,
        $private_key,
        $physical_interface,
        $listen_port = 51820,
        $mtu = 1420
    ) {
        $request = new \Proto\Wireguard\MountRequest();
        $request->setInterfaceName($interface_name);
        $request->setSubnet($subnet);
        $request->setAddress($gateway);
        $request->setPrivateKey($private_key);
        $request->setPhysicalInterface($physical_interface);
        $request->setListenPort($listen_port);
        $request->setMtu($mtu);

        $this->validateListenPort($listen_port);

        list($response, $status) = $this->getClient()->mount(
            $request,
            $this->getMetadata()
        )->wait();

        $this->assertGrpcStatus($status, 'mount');

        return $response->getMessage();
    }

    /**
     * Remove the Wireguard Network Interface
     * @param mixed $interface_name
     * @throws RuntimeException
     */
    public function removeInterface($interface_name)
    {
        $request = new \Proto\Wireguard\InterfaceRequest();
        $request->setInterfaceName($interface_name);

        list($response, $status) = $this->getClient()->umount(
            $request,
            $this->getMetadata()
        )->wait();

        $this->assertGrpcStatus($status, 'umount');

        return $response->getMessage();
    }

    /**
     * Shutdown the Wireguard Network Interface
     * @param mixed $interface_name
     * @throws RuntimeException
     */
    public function shutdownInterface($interface_name)
    {
        $request = new \Proto\Wireguard\InterfaceRequest();
        $request->setInterfaceName($interface_name);

        list($response, $status) = $this->getClient()->down(
            $request,
            $this->getMetadata()
        )->wait();

        $this->assertGrpcStatus($status, 'down');

        return $response->getMessage();
    }

    /**
     * Start the wireguard network interface
     * @param mixed $interface_name
     * @throws RuntimeException
     */
    public function startInterface($interface_name)
    {
        $request = new \Proto\Wireguard\InterfaceRequest();
        $request->setInterfaceName($interface_name);

        list($response, $status) = $this->getClient()->up(
            $request,
            $this->getMetadata()
        )->wait();

        $this->assertGrpcStatus($status, 'up');

        return $response->getMessage();
    }

    /**
     * Add new peer in the Wireguard Network Interface
     * @param string $userId
     * @param string $device_name
     * @param string $interface_name
     * @param string $public_key
     * @param string $allowed_ips
     * @param string $endpoint
     * @param string $preshared_key
     * @param string $persistent_keepalive
     * @throws RuntimeException
     */
    public function addPeer(
        string $userId,
        string $device_name,
        string $interface_name,
        string $public_key,
        string $allowed_ips,
        string $endpoint,
        string $preshared_key,
        string $persistent_keepalive
    ) {

        $request = new \Proto\Wireguard\AddPeerRequest();
        $request->setUserId($userId);
        $request->setDeviceName($device_name);
        $request->setInterfaceName($interface_name);
        $request->setPublicKey($public_key);
        $request->setAllowedIps($allowed_ips);
        $request->setEndpoint($endpoint);
        $request->setPresharedKey($preshared_key);
        $request->setPersistentKeepalive($persistent_keepalive);

        list($response, $status) = $this->getClient()->addPeer(
            $request,
            $this->getMetadata()
        )->wait();

        $this->assertGrpcStatus($status, 'addPeer');

        return $response->getMessage();
    }

    /**
     * Delete peer
     * @param mixed $interface_name
     * @param mixed $public_key
     * @throws RuntimeException
     */
    public function deletePeer($interface_name, $public_key)
    {
        $request = new \Proto\Wireguard\DeletePeerRequest();
        $request->setInterfaceName($interface_name);
        $request->setPublicKey($public_key);

        list($response, $status) = $this->getClient()->deletePeer(
            $request,
            $this->getMetadata()
        )->wait();

        $this->assertGrpcStatus($status, 'deletePeer');

        return $response->getMessage();
    }

    /**
     * Get the all interface available on the server
     * @throws RuntimeException
     * @return array{interface: mixed[]}
     */
    public function networkInterfaces()
    {
        list($response, $status) = $this->getClient()->interfaces(
            new \Proto\Wireguard\EmptyRequest(),
            $this->getMetadata()
        )->wait();

        $this->assertGrpcStatus($status, 'interfaces');

        $interfaces = [];
        foreach ($response->getData() as $interface) {
            $interfaces[] = [
                'interface' => $interface->getInterface(),
            ];
        }

        return $interfaces;
    }

    /**
     * Force to reload the wireguard network interface using the config file
     * @param mixed $interface_name
     * @throws RuntimeException
     */
    public function reloadNetwork($interface_name)
    {
        $request = new \Proto\Wireguard\InterfaceRequest();
        $request->setInterfaceName($interface_name);

        list($response, $status) = $this->getClient()->restart(
            $request,
            $this->getMetadata()
        )->wait();

        $this->assertGrpcStatus($status, 'restart');

        return $response->getMessage();
    }
}
