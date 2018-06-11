<?php
namespace BKTest\Controllers;

use BKTest\Models\iPersist;
use BKTest\Models\Router;

/**
 * Class ArticleController
 *
 * @author Brian Kroll <me@bckroll.com>
 */
class ArticleController extends CmsController
{
    /**
     * @return iPersist
     * @throws \Exception
     */
    protected function getPersistenceLayer()
    {
        return $this->di->get('articlePdo');
    }

    /**
     * @param array $result Result
     *
     * @return array
     */
    protected function singleResult($result)
    {
        return [
            'title' => $result['title'],
            'author' => $result['author'],
            'content' => $result['content'],
            'url' => Router::articleUrl($result['id']),
            'topic' => Router::topicUrl($result['topic'])
        ];
    }

    /**
     * @param string $parent
     * @param mixed $id
     *
     * @return \BKTest\Models\Response
     */
    public function listChildrenAction($parent, $id)
    {
        $result = $this->getPersistenceLayer()->selectAllByAttributeEquals($parent, $id);

        array_walk($result, function (&$result) {
            $result = $this->singleResult($result);
        });

        $this->response->setBody($result);

        return $this->response;
    }
}