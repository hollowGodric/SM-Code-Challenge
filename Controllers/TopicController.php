<?php
namespace BKTest\Controllers;

use BKTest\Models\iPersist;
use BKTest\Models\Router;

/**
 * Class TopicController
 *
 * @author Brian Kroll <me@bckroll.com>
 */
class TopicController extends CmsController
{
    /**
     * @return iPersist
     */
    protected function getPersistenceLayer()
    {
        return $this->di->get('topicPdo');
    }

    /**
     * @param mixed { Result
     *
     * @return mixed
     */
    protected function singleResult($topic)
    {
        return [
            'title'    => $topic['title'],
            'url'      => Router::topicUrl($topic['id']),
            'articles' => Router::articlesListUrl($topic['id'])
        ];
    }
}