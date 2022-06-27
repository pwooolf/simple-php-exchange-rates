<?php

namespace App\Services\ExchangeRates;

use Exception;
use Throwable;

class ExchangeRatesException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $messages = [];
        $errors = json_decode($message, true);
        foreach ($errors as $error) {
            $messages[] = $error;
        }

        $this->message = sprintf('Ошибки получения курсов валют: %s',
            implode("\n", $messages)
        );
    }
}