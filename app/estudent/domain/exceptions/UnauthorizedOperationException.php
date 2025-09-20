<?php

namespace App\estudent\domain\exceptions;

class UnauthorizedOperationException extends EstudentException
{
    protected $message = 'Unauthorized Operation';
    protected int $statusCode = 403;

    public function __construct(string $message = null)
    {
        parent::__construct($message ?? $this->message, $this->statusCode);
    }
}
