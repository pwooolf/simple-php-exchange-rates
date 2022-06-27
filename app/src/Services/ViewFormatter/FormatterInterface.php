<?php

namespace App\Services\ViewFormatter;

interface FormatterInterface
{
    public function getData(array $data): void;
    public function formatter();
}