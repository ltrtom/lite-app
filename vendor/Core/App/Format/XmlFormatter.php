<?php

namespace vendor\Core\App\Format;


class XmlFormatter implements FormatterInterface{

    public function format($data, $route)
    {
        return [];
    }

    public function isDefault()
    {
        return false;
    }

    public function getContentType()
    {
        return 'application/xml';
    }
}