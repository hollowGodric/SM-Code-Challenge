<?php
namespace BKTest\Controllers;

use BKTest\Models\iPersist;
use BKTest\Models\Response;

/**
 * Class CmsController
 *
 * @author Brian Kroll <me@bckroll.com>
 */
abstract class CmsController extends BaseController
{
    /**
     * Create a new record
     *
     * @return Response
     */
    public function createAction()
    {
        $insertId = $this->getPersistenceLayer()->insertRow($this->request->body);

        if ($insertId) {
            $data = ['id' => $insertId] + $this->request->body;
            $this->response->setBody($this->singleResult($data));
        } else {
            error_log("Insert returns . " . var_export($insertId, true));
            $this->response->setStatusCode(500);
        }

        return $this->response;
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws \Exception
     */
    public function selectAction($id)
    {
        $result = $this->getPersistenceLayer()->selectById($id);

        $this->response->setBody($this->singleResult($result));

        return $this->response;
    }

    /**
     * @param mixed $id
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        $this->response->setBody($this->getPersistenceLayer()->delete($id));

        return $this->response;
    }

    /**
     * @return Response
     */
    public function listAction()
    {
        $result = $this->getPersistenceLayer()->getAll();
        array_walk($result, function (&$topic) {
            $topic = $this->singleResult($topic);
        });

        $this->response->setBody($result);

        return $this->response;
    }

    /**
     * @return iPersist
     */
    abstract protected function getPersistenceLayer();

    /**
     * @param mixed $result Result
     *
     * @return mixed
     */
    abstract protected function singleResult($result);
}

