<?php

namespace Vpn\App\Wrapper;

use Vpn\Vendor\Grpc\ChannelCredentials;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Vpn\App\Services\KeysGenerator;

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

class System
{
    /**
     * gRPC client
     * @var mixed
     */
    protected $client;

    /**
     * Endpoint
     * @var string
     */
    protected string $endpoint;

    /**
     * Port
     * @var int
     */
    protected int $port;

    /**
     * Token JWT
     * @var string
     */
    protected string $token;


    public const OK = 0;
    public const CANCELLED = 1;
    public const UNKNOWN = 2;
    public const INVALID_ARGUMENT = 3;
    public const DEADLINE_EXCEEDED = 4;
    public const NOT_FOUND = 5;
    public const ALREADY_EXISTS = 6;
    public const PERMISSION_DENIED = 7;
    public const RESOURCE_EXHAUSTED = 8;
    public const FAILED_PRECONDITION = 9;
    public const ABORTED = 10;
    public const OUT_OF_RANGE = 11;
    public const UNIMPLEMENTED = 12;
    public const INTERNAL = 13;
    public const UNAVAILABLE = 14;
    public const DATA_LOSS = 15;
    public const UNAUTHENTICATED = 16;

    /**
     * Clase del cliente gRPC
     * @var string
     */
    protected string $clientClass;

    public function __construct(string $endpoint, int $port = 50051, string $clientClass)
    {
        $this->endpoint = $endpoint;
        $this->port = $port;
        $this->clientClass = $clientClass;

        $this->initializeClient();
    }

    /**
     * initializeClient
     * @return void
     */
    private function initializeClient(): void
    {
        $target = "{$this->endpoint}:{$this->port}";

        $this->client = new $this->clientClass(
            $target,
            [
                'credentials' => ChannelCredentials::createInsecure(),
                'grpc.keepalive_timeout_ms' => 5000,
                'grpc.keepalive_time_ms' => 10000,
                'grpc.keepalive_permit_without_calls' => 1,
            ]
        );
    }

    /**
     * Get client
     * @return object
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get metadata
     * @return string[][]
     */
    public function getMetadata(): array
    {
        $keyGenerator = app(KeysGenerator::class);
        $token = $keyGenerator->generateToken();

        return [
            'authorization' => [$token]
        ];
    }

    /**
     * Check the port is valid
     * @param int $port
     * @throws ReportError
     * @return void
     */
    protected function validateListenPort(int $port): void
    {
        if ($port < 1024 || $port > 65535) {
            throw new ReportError(
                __('The selected port is not valid. Please choose a port between 1024 and 65535.'),
                403
            );
        }
    }


    /**
     * Transform data and grpc code to http
     * @param int $code
     * @return array{message: string, status: int}
     */
    public static function grpcToHttp(int|string $code): array
    {
        $code = (int) $code;

        return match ($code) {
            self::OK => [
                'status' => 200,
                'message' => 'Everything went well. Your request was completed successfully.',
            ],

            self::CANCELLED => [
                'status' => 499,
                'message' => 'The operation was cancelled before it finished.',
            ],

            self::INVALID_ARGUMENT => [
                'status' => 400,
                'message' => 'Some of the information you sent is not valid. Please check and try again.',
            ],

            self::DEADLINE_EXCEEDED => [
                'status' => 504,
                'message' => 'The server took too long to respond. Please try again.',
            ],

            self::NOT_FOUND => [
                'status' => 404,
                'message' => 'We couldn’t find what you were looking for.',
            ],

            self::ALREADY_EXISTS => [
                'status' => 409,
                'message' => 'This already exists, so it cannot be created again.',
            ],

            self::PERMISSION_DENIED => [
                'status' => 403,
                'message' => 'You don’t have permission to do this.',
            ],

            self::UNAUTHENTICATED => [
                'status' => 401,
                'message' => 'Please log in again. Your session may have expired.',
            ],

            self::RESOURCE_EXHAUSTED => [
                'status' => 429,
                'message' => 'The system is busy right now. Please wait a moment and try again.',
            ],

            self::FAILED_PRECONDITION => [
                'status' => 412,
                'message' => 'This action can’t be completed right now because the system is not ready.',
            ],

            self::ABORTED => [
                'status' => 409,
                'message' => 'The operation was stopped. Please try again.',
            ],

            self::OUT_OF_RANGE => [
                'status' => 416,
                'message' => 'One of the values is outside the allowed range.',
            ],

            self::UNIMPLEMENTED => [
                'status' => 501,
                'message' => 'This feature is not available yet.',
            ],

            self::INTERNAL => [
                'status' => 500,
                'message' => 'Something went wrong on our side. Please try again later.',
            ],

            self::UNAVAILABLE => [
                'status' => 503,
                'message' => 'The service is temporarily unavailable. Please try again in a few moments.',
            ],

            self::DATA_LOSS => [
                'status' => 500,
                'message' => 'A serious system error occurred. Please contact support.',
            ],

            self::UNKNOWN => [
                'status' => 500,
                'message' => 'An unexpected error occurred. Please try again.',
            ],

            default => [
                'status' => 500,
                'message' => 'Unexpected system error.',
            ],
        };
    }
}
