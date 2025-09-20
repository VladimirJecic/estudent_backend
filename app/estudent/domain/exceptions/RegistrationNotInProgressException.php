<?php

namespace App\estudent\domain\exceptions;

class RegistrationNotInProgressException extends EstudentException
{
    protected $message = 'Registration not allowed';
    protected int $statusCode = 400;

    public function __construct(string $message = null)
    {
        parent::__construct($message ?? $this->message, $this->statusCode);
    }
}
