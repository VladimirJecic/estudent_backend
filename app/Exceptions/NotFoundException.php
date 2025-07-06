<?php

namespace App\Exceptions;

class NotFoundException extends EstudentException
{
    protected $message = 'Not Found';
    protected int $statusCode = 404;

    public function __construct(string $message = null)
    {
        parent::__construct($message ?? $this->message, $this->statusCode);
    }
}
