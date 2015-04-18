<?php

namespace vendor\Core\App\Format;

use vendor\Core\App\Format\FormatterInterface;

class JsonFormatter implements FormatterInterface{

    public function format($data, $route)
    {
        return json_encode(
            empty($data) ? [] : $data
        );
    }

    public function isDefault()
    {
        return true;
    }

    public function getContentType()
    {
        return 'application/json';
    }
}
