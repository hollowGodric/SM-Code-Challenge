<?php
namespace BKTest\Controllers;

use BKTest\Models\Container;
use BKTest\Models\Request;
use BKTest\Models\Response;

/**
 * Base Controller
 *
 * @author Brian Kroll <me@bckroll.com>
 */
abstract class BaseController
{
    /** @var Request */
    protected $request;

    /** @var Response */
    protected $response;

    /** @var Container */
    public $di;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->di = Container::getInstance();
    }
}