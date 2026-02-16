<?php

namespace Vpn\App\Services;

use Vpn\App\Wrapper\Core;
use Vpn\App\Models\Wireguard;
use App\Repositories\Traits\Scopes;
use Vpn\App\Repositories\PeerRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Vpn\App\Repositories\ServerRepository;

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

class MasterService
{
    use Scopes;

    /**
     * Plans
     * @var array
     */
    protected $plans_vpn;

    /**
     * Plans vpn servers
     * @var array
     */
    protected $plans_vpn_servers;


    public function __construct()
    {
        $this->plans_vpn = [
            'commerce:vpn:professional' => config_module('plans.peers.professional', 20),
            'commerce:vpn:advanced' => config_module('plans.peers.advanced', 20),
            'commerce:vpn:intermediate' => config_module('plans.peers.intermediate', 10),
            'commerce:vpn:basic' => config_module('plans.peers.basic', 5),
            'commerce:vpn:free' => config_module('plans.peers.free', 2),
        ];

        $this->plans_vpn_servers = [
            'commerce:vpn-servers:professional' => config_module('plans.servers.professional', 5),
            'commerce:vpn-servers:advanced' => config_module('plans.servers.advanced', 3),
            'commerce:vpn-servers:intermediate' => config_module('plans.servers.intermediate', 2),
            'commerce:vpn-servers:basic' => config_module('plans.servers.basic', 1),
            'commerce:vpn-servers:free' => config_module('plans.servers.free', 0),
        ];
    }


    /**
     * Callable grpc function
     * @param callable $fn
     * @throws ReportError
     * @return void
     */
    public function grpc(callable $fn)
    {
        try {
            $fn();
        } catch (\Throwable $th) {
            if ($th->getCode() <= 16) {
                $status = Core::grpcToHttp($th->getCode());
                throw new ReportError(__($status['message']), $status['status']);
            }

            throw new ReportError(__($th->getMessage()), $th->getCode());
        }
    }

    /**
     * Core
     * @param Wireguard $model
     * @return Core
     */
    public function core(Wireguard $model): Core
    {
        return new Core($model->server->ip, $model->server->port);
    }

    /**
     * Generate a PrivKey
     * @return string
     */
    public function generatePrivKey()
    {
        return trim(shell_exec('wg genkey'));
    }

    /**
     * Generate a public key
     * @return string
     */
    public function generatePubKey(string $private_key)
    {
        return trim(shell_exec("echo {$private_key} | wg pubkey"));
    }

    /**
     *  Generate a pair keys (private and public key)
     * @return string[]
     */
    public function generatePairKeys()
    {
        $private_key = trim(shell_exec('wg genkey'));
        $public_key = trim(shell_exec("echo {$private_key} | wg pubkey"));
        return ['private_key' => $private_key, 'public_key' => $public_key];
    }

    /**
     * Generate a Preshared key for the config peer
     * @return string
     */
    public function generatePresharedkey()
    {
        return trim(shell_exec('wg genpsk'));
    }

    /**
     * generateNextSubnet
     * @param mixed $subnet
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return array
     */
    public function generateNextSubnet($subnet = null)
    {
        // Default safe private range base: 10.100.0.0/16
        $subnet_base = $subnet ?? "10.100.0.0/16";

        list($ip, $prefix) = explode('/', $subnet_base);

        // Validate IP format
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new ReportError(__('Invalid IP address'), 403);
        }

        $prefix = (int) $prefix;
        // Only /16 prefixes are allowed for consistency
        if ($prefix !== 16) {
            throw new ReportError(__('Only /16 prefixes are supported'), 403);
        }

        // Ensure the IP is within a private RFC1918 range
        if (!self::isPrivateIP($ip)) {
            throw new ReportError(__('Subnet must be within a private IP range (RFC1918)'), 403);
        }

        $ip_long = ip2long($ip);
        $increment = 1 << (32 - $prefix);
        $next_ip_long = $ip_long + $increment;

        // Ensure the next IP stays within the private range
        $private_limit = ip2long("10.255.255.255"); // assuming usage of 10.0.0.0/8
        if ($next_ip_long > $private_limit) {
            throw new ReportError(__('IP limit exceeded for the private range'), 403);
        }

        // Set gateway address to x.x.x.1
        $gateway_parts = explode('.', long2ip($next_ip_long));
        $gateway_parts[3] = 1;

        return [
            "subnet" => long2ip($next_ip_long),
            "gateway" => implode('.', $gateway_parts),
            "prefix" => $prefix
        ];
    }

    /**
     * Checks if an IP address is within a private RFC1918 range.
     * @param string $ip
     * @return bool
     */
    public static function isPrivateIP($ip)
    {
        $long = ip2long($ip);
        return (
            ($long >= ip2long('10.0.0.0') && $long <= ip2long('10.255.255.255')) ||
            ($long >= ip2long('172.16.0.0') && $long <= ip2long('172.31.255.255')) ||
            ($long >= ip2long('192.168.0.0') && $long <= ip2long('192.168.255.255'))
        );
    }

    /**
     * Set the limit to add devices for the user
     * @param mixed $user
     * @return void
     */
    public function verifyVpnPlan($user)
    {
        //Retrieve the all vpn device (Wireguard protocol)
        $wireguard = app(PeerRepository::class)
            ->query()
            ->where('user_id', $user->id);

        $access = collect($this->scopes(true, true))->pluck('id');

        //check user plan
        $userLimit = collect($this->plans_vpn)
            ->filter(fn ($limit, $plan) => $access->contains($plan))
            ->first() ?? config_module('plans.peers.free', 2);

        throw_if(
            $wireguard->count() >= $userLimit,
            new ReportError(
                __('You have exceeded the server limit. To add more servers, please upgrade to a higher plan.'),
                403
            )
        );
    }

    public function verifyVpnServerPlan($user)
    {
        //Retrieve the all vpn device (Wireguard protocol)
        $servers = app(ServerRepository::class)->query()->where('user_id', $user->id);

        $access = collect($this->scopes(true, true))->pluck('id');

        //check user plan
        $userLimit = collect($this->plans_vpn_servers)
            ->filter(fn ($limit, $plan) => $access->contains($plan))
            ->first() ?? config_module('plans.servers.free', 0);
         
        throw_if(
            $servers->count() >= $userLimit,
            new ReportError(
                __('You have exceeded the device limit. To add more devices, please upgrade to a higher plan.'),
                403
            )
        );
    }



    /**
     * Summary of generateRandomIp
     * @param mixed $subnet
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return bool|string
     */
    public function generateRandomIp($subnet, $attempts = 10)
    {
        list($baseIp, $mask) = explode('/', $subnet);

        $mask = (int) $mask;

        $baseIpLong = ip2long($baseIp);
        $hostCount = 2 ** (32 - $mask) - 2;

        if ($hostCount < 1) {
            throw new ReportError(__("The specified subnet does not allow valid host addresses."), 422);
        }

        for ($i = 0; $i < $attempts; $i++) {
            $randomOffset = rand(1, $hostCount);
            $randomIpLong = $baseIpLong + $randomOffset;
            $randomIp = long2ip($randomIpLong);

            if (filter_var($randomIp, FILTER_VALIDATE_IP)) {
                $exists = $this->verifyIpExists($randomIp);

                if (!$exists) {
                    return $randomIp;
                }
            }
        }

        throw new ReportError(
            __("Unable to generate a valid IP address after :attempts attempts. The current server may be overloaded or unable to allocate new addresses. Please consider using a different server.", ['attempts' => $attempts]),
            422
        );
    }

    /**
     * Generate unique IP
     *
     * @param string $subnet
     * @return string
     */
    public function generateUniqueIp($subnet = "10.0.0.0/8")
    {
        list($baseIP, $prefix) = explode('/', $subnet);
        $baseIP = ip2long($baseIP);

        $totalIPs = pow(2, (32 - $prefix));

        $randomIP = $baseIP + rand(1, $totalIPs - 2);

        return long2ip($randomIP) . "/32";
    }

    /**
     * Check the ip address
     * @param mixed $ip
     */
    public function verifyIpExists($ip)
    {
        $peer = app(PeerRepository::class)
            ->query()
            ->where('allowed_ips', "=", $ip)
            ->first();
        return $peer ?? false;
    }

    /**
     * Retrieve user plan
     * @return array{user_plan: array{total_devices: mixed, used_devices: int}}
     */
    public function userPlan()
    {
        $access = collect($this->scopes(true, false))->pluck('id');

        //check user plan
        $amount = collect($this->plans_vpn)
            ->filter(fn ($limit, $plan) => $access->contains($plan))
            ->first() ?? config('vpn.free');


        $count_peer = app(PeerRepository::class)
            ->query()
            ->where('user_id', request()->user()->id)
            ->count();

        return [
            'used_devices' => $count_peer,
            'total_devices' => $amount
        ];
    }
}
