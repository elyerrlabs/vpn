<?php

namespace Vpn\App\Wrapper;

use Grpc\ChannelCredentials;
//use Vpn\App\Models\KeyGenerator;

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
    public const RESOURCE_EXHAUSTED = 8 ;
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
    /*public function getMetadata(): array
    {
        $keyGenerator = app(KeyGenerator::class);
        $token = $keyGenerator->generateToken();

        return [
            'authorization' => [$token]
        ];
    }*/

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
                'message' => 'The operation was completed successfully.',
            ],

            self::CANCELLED => [
                'status' => 499,
                'message' => 'The request was cancelled by the client before completion.',
            ],

            self::INVALID_ARGUMENT => [
                'status' => 400,
                'message' => 'One or more request parameters are invalid or malformed.',
            ],

            self::DEADLINE_EXCEEDED => [
                'status' => 504,
                'message' => 'The server did not respond within the expected time limit.',
            ],

            self::NOT_FOUND => [
                'status' => 404,
                'message' => 'The requested resource could not be found on the server.',
            ],

            self::ALREADY_EXISTS => [
                'status' => 409,
                'message' => 'The resource already exists and cannot be created again.',
            ],

            self::PERMISSION_DENIED => [
                'status' => 403,
                'message' => 'You do not have permission to perform this operation.',
            ],

            self::UNAUTHENTICATED => [
                'status' => 401,
                'message' => 'Authentication is required or the provided credentials are invalid.',
            ],

            self::RESOURCE_EXHAUSTED => [
                'status' => 429,
                'message' => 'Resource limits have been exceeded. Please retry later.',
            ],

            self::FAILED_PRECONDITION => [
                'status' => 412,
                'message' => 'The operation cannot be executed due to the current system state.',
            ],

            self::ABORTED => [
                'status' => 409,
                'message' => 'The operation was aborted, usually due to a concurrency conflict.',
            ],

            self::OUT_OF_RANGE => [
                'status' => 416,
                'message' => 'The requested operation exceeds the valid range.',
            ],

            self::UNIMPLEMENTED => [
                'status' => 501,
                'message' => 'This operation is not implemented or not supported by the server.',
            ],

            self::INTERNAL => [
                'status' => 500,
                'message' => 'An internal server error occurred. Please try again later.',
            ],

            self::UNAVAILABLE => [
                'status' => 503,
                'message' => 'The service is temporarily unavailable. Please retry with backoff.',
            ],

            self::DATA_LOSS => [
                'status' => 500,
                'message' => 'Unrecoverable data loss or corruption was detected.',
            ],

            self::UNKNOWN => [
                'status' => 500,
                'message' => 'An unknown error occurred while processing the request.',
            ],

            default => [
                'status' => 500,
                'message' => 'Unhandled gRPC status code received from server.',
            ],
        };
    }

}
