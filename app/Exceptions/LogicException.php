<?php

namespace App\Exceptions;


use Throwable;

class LogicException extends \LogicException
{
    public function __construct(string $message = "", int $code = ErrorConstant::LOGIC_ERR, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}