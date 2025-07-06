<?php

namespace App\Exceptions;

class BadRequestException extends EstudentException
{
    protected $message = 'Bad request';
    protected int $statusCode = 400;

    public function __construct(string $message = null)
    {
        parent::__construct($message ?? $this->message, $this->statusCode);
    }
}
