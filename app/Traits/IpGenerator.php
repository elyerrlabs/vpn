<?php
namespace Vpn\App\Traits;

use Elyerr\ApiResponse\Exceptions\ReportError;

trait IpGenerator
{

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
}
