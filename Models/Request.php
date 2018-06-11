<?php
namespace BKTest\Models;

/**
 * Class Request
 *
 * @package BKTest\Models
 * @author Brian Kroll <me@bckroll.com>
 */
class Request
{
    /**
     * @param array|\ArrayAccess $config
     */
    public function __construct($config)
    {
        $this->requestUri = explode('?', $_SERVER['REQUEST_URI'])[0];
        $this->rawBody    = file_get_contents($config['source']); // default "php://input"
        $this->body       = json_decode($this->rawBody, true);
    }

    /**
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->body->$key) ? $this->body->$key : $default;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }
}