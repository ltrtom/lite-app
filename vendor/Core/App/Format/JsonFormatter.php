<?php

namespace vendor\Core\App\Format;


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
