<?php

namespace App\Service;

use App\Enum\HttpStatus;
use App\Model\ResultDto;

readonly abstract class AbstractService
{
    public function makeResultDto(
        bool $ok,
        mixed $data,
        string $detail = '',
        HttpStatus $status = HttpStatus::Ok
    ): ResultDto
    {
        return new ResultDto(ok: $ok, data: $data, detail: $detail, status: $status);
    }
}