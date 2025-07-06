<?php

namespace App\Exceptions;

use Exception;

abstract class EstudentException extends Exception
{
    protected int $statusCode = 500;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
