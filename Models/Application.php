<?php
namespace BKTest\Models;

use BKTest\Database;

/**
 * Class Application
 *
 * @package BKTest\Models
 * @author Brian Kroll <me@bckroll.com>
 */
class Application
{
    protected $controller;

    protected $action;

    protected $args;

    /**
     * Configuration
     * Could be in a file, but here to save time
     *
     * @var array
     */
    public $config = [
        'database' => [
            'host' => '127.0.0.1',
            'user' => 'root',
            'pass' => 'searchmetrics',
            'dbname' => 'searchmtest',
            'driver'  => 'mysql'
        ],
        'request' => [
            'source' => "php://input"
        ]
    ];

    /**
     * Bootstrap the application
     *
     * @return void
     * @throws \Exception
     */
    public function bootstrap()
    {
        $this->di();
        $request = new Request($this->config['request']);
        $response = new Response();
        $router = new Router($request->requestUri, $_SERVER['REQUEST_METHOD']);
        $controllerClass = $router->getController();
        $this->controller = new $controllerClass($request, $response);
        $this->action = $router->getAction();
        $this->args = $router->getVars();
    }

    /**
     * Run the Application
     *
     * @return mixed
     */
    public function run()
    {
        return call_user_func_array([$this->controller, $this->action], $this->args);
    }

    /**
     * Dependency Injection
     *
     * @return Container
     */
    protected function di()
    {
        $di = Container::getInstance();
        $di->set('config', $this->config);
        $di->set('articlePdo', function() use ($di) {
            return new Database\ArticlePdo($di->pdoDriver);
        });
        $di->set('topicPdo', function() use ($di) {
            return new Database\TopicPdo($di->pdoDriver);
        });
        $di->set('pdoDriver', function() use ($di) {
            $config = $di->config['database'];
            $dsn = $config['driver'];
            $user = isset($config['user']) ? $config['user'] : null;
            $pass = isset($config['pass']) ? $config['pass'] : null;

            $options = array_intersect_key($config, array_flip(['dbname', 'host']));
            $optionString = implode(';', array_map(function ($key, $value) {
                return "$key=$value";
            }, array_keys($options), $options));

            $dsn .= empty($options) ?: ":$optionString";

            return new \PDO($dsn, $user, $pass);
        });
        return $di;
    }
}