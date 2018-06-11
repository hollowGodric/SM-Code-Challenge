<?php
namespace BKTest\Models;

/**
 * Class Response
 *
 * @package BKTest\Models
 * @author Brian Kroll <me@bckroll.com>
 */
class Response
{
    protected $body = [];

    /**
     * @param array|string $body
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->body = (array) $body;
    }

    /**
     * @param int $code
     *
     * @return void
     */
    public function setStatusCode($code)
    {
        http_response_code($code);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        return json_encode($this->body);
    }
}