<?php

namespace App\estudent\domain\exceptions;

class RegistrationAlreadyExistsException extends EstudentException
{
    protected $message = 'Registration already exists';
    protected int $statusCode = 409;

    public function __construct(string $message = null)
    {
        parent::__construct($message ?? $this->message, $this->statusCode);
    }
}
