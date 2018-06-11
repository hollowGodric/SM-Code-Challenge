<?php
namespace BKTest\Models;

/**
 * Class Router
 * @package BKTest\Models
 * @author Brian Kroll <me@bckroll.com>
 */
class Router
{
    /** @var array */
    private $map = [
        'articles' => 'BKTest\Controllers\ArticleController',
        'topics' => 'BKTest\Controllers\TopicController',
    ];

    /** @var string */
    private $subject = 'topics';

    /** @var array */
    private $vars = [];

    /**
     * @param string $route
     * @param string $method
     */
    public function __construct($route, $method)
    {
        $this->route = $route;
        $parts = explode('/', $route);
        if ($parts[0] === '') {
            array_shift($parts);
        }

        $this->subject = array_shift($parts);
        $this->vars = $parts;
        $this->httpMethod = $method;
    }

    /**
     * Get Controller
     *
     * @return string
     * @throws \Exception
     */
    public function getController()
    {
        if (isset($this->map[ $this->subject ])) {
            return $this->map[ $this->subject ];
        }
        throw new \Exception('No controller mapped for ' . $this->subject);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getAction()
    {
        if ($this->httpMethod == 'PUT') {
            return 'createAction';
        } elseif ($this->httpMethod == 'DELETE') {
            return 'deleteAction';
        } elseif ($this->httpMethod == 'GET') {
            if (!isset($this->vars[0])) {
                return 'listAction';
            } elseif (is_numeric($this->vars[0])) {
                return 'selectAction';
            } elseif ($this->subject == 'articles' && $this->vars[0] == 'topic') {
                return 'listChildrenAction';
            } else {
                throw new \Exception('Could not route ' . $this->route);
            }
        }
        throw new \Exception($this->httpMethod . ' not implemented');
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @param string|int $id
     *
     * @return string
     */
    public static function topicUrl($id)
    {
        return "/topics/$id";
    }

    /**
     * @param string|int $id
     *
     * @return string
     */
    public static function articlesListUrl($id)
    {
        return "/articles/topic/$id";
    }

    /**
     * @param string|int $id
     *
     * @return string
     */
    public static function articleUrl($id)
    {
        return "/articles/$id";
    }
}