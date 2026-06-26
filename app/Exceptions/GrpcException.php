<?php

namespace Vpn\App\Exceptions;

use RuntimeException;
use Throwable;
use Vpn\App\Wrapper\System;

class GrpcException extends RuntimeException
{
    protected int $grpcCode;

    protected string $grpcDetails;

    protected ?string $method;

    public function __construct(int $grpcCode, string $grpcDetails = '', ?string $method = null, ?Throwable $previous = null)
    {
        $this->grpcCode = $grpcCode;
        $this->grpcDetails = $grpcDetails;
        $this->method = $method;

        parent::__construct($this->buildMessage(), $grpcCode, $previous);
    }

    public function getGrpcCode(): int
    {
        return $this->grpcCode;
    }

    public function getGrpcDetails(): string
    {
        return $this->grpcDetails;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function getHttpStatus(): int
    {
        return System::grpcToHttp($this->grpcCode)['status'];
    }

    private function buildMessage(): string
    {
        $status = System::grpcToHttp($this->grpcCode);
        $message = $this->grpcDetails !== '' ? $this->grpcDetails : $status['message'];

        if ($this->method) {
            return "gRPC {$this->method} failed ({$this->grpcCode}): {$message}";
        }

        return "gRPC failed ({$this->grpcCode}): {$message}";
    }
}
