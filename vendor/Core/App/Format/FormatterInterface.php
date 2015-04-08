<?php

namespace vendor\Core\App\Format;


interface FormatterInterface {

    public function format($data, $route);

    public function isDefault();

    public function getContentType();

}