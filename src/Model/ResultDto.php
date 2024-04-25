<?php

namespace App\Model;

use App\Enum\HttpStatus;

class ResultDto
{
    public function __construct(
        private bool $ok,
        private mixed $data = null,
        private string $detail = '',
        private HttpStatus $status = HttpStatus::Ok,
    ) {
    }

    public function hasErrors(): bool
    {
        return ! $this->ok;
    }

    public function isOk(): bool
    {
        return $this->ok;
    }

    public function setOk(bool $ok): static
    {
        $this->ok = $ok;

        return $this;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    public function getStatus(): ?HttpStatus
    {
        return $this->status;
    }

    public function setStatus(HttpStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}
