<?php

namespace App\Services\ViewFormatter;

abstract class AbstractFormatter implements FormatterInterface
{
    protected array $data;

    public function getData(array $data): void
    {
        $this->data = $data;
    }
}