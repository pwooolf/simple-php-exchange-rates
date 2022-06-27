<?php

namespace App\Services\ViewFormatter;

class JsonFormatter extends AbstractFormatter
{
    public function formatter()
    {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($this->data);
    }
}