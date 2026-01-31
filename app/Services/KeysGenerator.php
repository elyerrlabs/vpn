<?php

namespace Vpn\App\Services;

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

class KeysGenerator
{
    /**
     * Path
     * @var string
     */
    public string $path;

    /**
     * Private key
     * @var string
     */
    private string $privateKeyPath;

    /**
     * Public key
     * @var string
     */
    private string $publicKeyPath;


    public function __construct()
    {
        $this->path = __DIR__ . "/../../secrets";

        $this->privateKeyPath = "{$this->path}/priv.pem";
        $this->publicKeyPath = "{$this->path}/pub.pem";

        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    /**
     * Generate keys
     * @param bool $overwrite
     * @return bool
     */
    public function generateKeys(bool $overwrite = false): bool
    {
        if (!$overwrite && file_exists($this->privateKeyPath)) {
            return false;
        }

        $res = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);

        openssl_pkey_export($res, $privateKey);

        $publicKey = openssl_pkey_get_details($res)['key'];

        file_put_contents($this->privateKeyPath, $privateKey);
        file_put_contents($this->publicKeyPath, $publicKey);

        return true;
    }

    /**
     * Generate a new token
     * @return string
     */
    public function generateToken(): string
    {
        $payload = json_encode([
            "iat" => time(),
            "iss" => "vpn-manager"
        ]);

        $privateKey = openssl_pkey_get_private(file_get_contents($this->privateKeyPath));

        openssl_sign($payload, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($payload) . '.' . base64_encode($signature);
    }

    /**
     * Validate token
     * @param string $token
     * @return bool
     */
    public function validateToken(string $token): bool
    {
        $parts = explode('.', $token);

        if (count($parts) !== 2) {
            return false;
        }

        [$payload64, $sig64] = $parts;

        $payload = base64_decode($payload64);
        $signature = base64_decode($sig64);

        $publicKey = openssl_pkey_get_public(file_get_contents($this->publicKeyPath));

        if (openssl_verify($payload, $signature, $publicKey, OPENSSL_ALGO_SHA256) !== 1) {
            return false;
        }

        $data = json_decode($payload, true);

        if (!isset($data['iat'], $data['iss'])) {
            return false;
        }

        if ($data['iss'] !== 'vpn-manager') {
            return false;
        }

        return (time() - $data['iat']) <= 15;
    }
}
