<?php

namespace BKTest\Models;

/**
 * Class Container
 *
 * @package BKTest\Models
 * @author Brian Kroll <me@bckroll.com>
 */
class Container
{
    protected $services = [];

    static $instance;

    /**
     * Singleton
     */
    private function __construct(){}

    /**
     * Singleton
     */
    private function __clone(){}

    /**
     * Return THE instance of container
     *
     * @return Container
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Container();
        }
        return self::$instance;
    }

    /**
     * Add a service
     *
     * @param string $name
     * @param callable|object $service
     *
     * @return void
     */
    public function set($name, $service)
    {
        $this->services[$name] = $service;
    }

    /**
     * Fetch a service
     *
     * @param string $name
     *
     * @return mixed
     * @throws \Exception
     */
    public function get($name)
    {
        if (! isset($this->services[$name])) {
            throw new \Exception('Service ' . $name . ' not defined');
        } else {
            $service = $this->services[$name];
        }

        return (is_callable($service)) ? $service() : $service;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        return $this->get($name);
    }
}