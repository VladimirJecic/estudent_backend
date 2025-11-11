<?php

namespace App\estudent\domain\exceptions;

class ExamRegistrationNotFoundException extends EstudentException
{
    protected $message = 'Not Found';
    protected int $statusCode = 404;

    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? $this->message, $this->statusCode);
    }
}
