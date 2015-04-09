<?php

namespace vendor\Core\App\Http\Exception;


class HttpException extends \Exception {

    /**
     * @var int
     */
    protected $httpCode;

    /**
     * @var string
     */
    protected $header;

    /**
     * @var string
     */
    protected $content;

    public function __construct($httpCode, $header, $content = '') {
        $this->httpCode = $httpCode;
        $this->header = $header;
        $this->content = $content;
    }

    public function getHttpCode() {
        return $this->httpCode;
    }

    public function getHeader() {
        return $this->header;
    }

    public function getContent() {
        return $this->content;
    }

}
