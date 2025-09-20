<?php

namespace App\estudent\domain\exceptions;

use Exception;

abstract class EstudentException extends Exception
{
    protected int $statusCode = 500;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
