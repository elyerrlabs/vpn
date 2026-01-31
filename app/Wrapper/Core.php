<?php

namespace Vpn\App\Wrapper;

use Elyerr\ApiResponse\Exceptions\ReportError;

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
     * @param int $port
     * @param string $host
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
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
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

        if ($status->code != self::OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }

    /**
     * Remove the Wireguard Network Interface
     * @param mixed $interface_name
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function removeInterface($interface_name)
    {
        $request = new \Proto\Wireguard\InterfaceRequest();
        $request->setInterfaceName($interface_name);

        list($response, $status) = $this->getClient()->umount(
            $request,
            $this->getMetadata()
        )->wait();

        if ($status->code != self::OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }

    /**
     * Shutdown the Wireguard Network Interface
     * @param mixed $interface_name
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function shutdownInterface($interface_name)
    {
        $request = new \Proto\Wireguard\InterfaceRequest();
        $request->setInterfaceName($interface_name);

        list($response, $status) = $this->getClient()->down(
            $request,
            $this->getMetadata()
        )->wait();

        if ($status->code != self::OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }

    /**
     * Start the wireguard network interface
     * @param mixed $interface_name
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function startInterface($interface_name)
    {
        $request = new \Proto\Wireguard\InterfaceRequest();
        $request->setInterfaceName($interface_name);

        list($response, $status) = $this->getClient()->up(
            $request,
            $this->getMetadata()
        )->wait();

        if ($status->code != self::OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }

    /**
     * Add new peer in the Wireguard Network Interface
     * @param mixed $userId
     * @param mixed $device_name
     * @param mixed $interface_name
     * @param mixed $public_key
     * @param mixed $allowed_ips
     * @param mixed $endpoint
     * @param mixed $preshared_key
     * @param mixed $persistent_keepalive
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function addPeer(
        $userId,
        $device_name,
        $interface_name,
        $public_key,
        $allowed_ips,
        $endpoint,
        $preshared_key,
        $persistent_keepalive
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

        if ($status->code != self::OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }

    /**
     * Delete peer
     * @param mixed $interface_name
     * @param mixed $public_key
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
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

        if ($status->code != self::OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }

    /**
     * Get the all interface available on the server
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function networkInterfaces()
    {
        list($response, $status) = $this->getClient()->interfaces(
            new \Proto\Wireguard\EmptyRequest(),
            $this->getMetadata()
        )->wait();

        if ($status->code != self::OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

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
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function reloadNetwork($interface_name)
    {
        $request = new \Proto\Wireguard\InterfaceRequest();
        $request->setInterfaceName($interface_name);

        list($response, $status) = $this->getClient()->restart(
            $request
        )->wait();

        if ($status->code != self::OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }
}
