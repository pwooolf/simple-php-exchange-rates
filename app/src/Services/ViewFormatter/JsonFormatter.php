<?php

namespace App\Services\ViewFormatter;

class JsonFormatter extends AbstractFormatter
{
    private const CONTENT_TYPE = 'application/json';

    public function show()
    {
        header(sprintf('Content-type: %s; charset=utf-8', self::CONTENT_TYPE));
        echo json_encode($this->data);
    }
}