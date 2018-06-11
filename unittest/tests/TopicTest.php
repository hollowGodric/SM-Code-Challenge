<?php

/**
 * Created by PhpStorm.
 * User: Brian
 * Date: 24/08/2015
 * Time: 23:00
 */
class TopicTest extends TestCase
{
    public function setUp()
    {
        $_SERVER = [];
        $this->resetMock(MockPDO::class);
        $this->resetMock(MockStatement::class);
    }

    protected function setRequestUri($uri)
    {
        $_SERVER['REQUEST_URI'] = $uri;
    }

    protected function setRequestMethod($method)
    {
        $_SERVER['REQUEST_METHOD'] = $method;
    }

    protected function bootstrapApp($query, $method, $config = null)
    {
        $this->setRequestUri($query);
        $this->setRequestMethod($method);
        $app = new \BKTest\Models\Application();
        isset($config) && $app->config = $config;
        $app->bootstrap();
        return $app;
    }

    public function testSelectTopic()
    {
        $mockPdo = new MockPDO();

        $app = $this->bootstrapApp('/topics/15', 'GET');
        $di = \BKTest\Models\Container::getInstance();
        $di->set('pdoDriver', $mockPdo);

        $dbResult = [
            'title' => 'testTitle',
            'id' => '15',
        ];

        $this->stub(MockStatement::class, 'fetch', [PDO::FETCH_ASSOC], $dbResult);

        $this->stub(
            MockPDO::class,
            'query',
            ['SELECT id, title FROM topic WHERE id = 15;'],
            new MockStatement()
        );

        $result = $app->run();

        $response = new \BKTest\Models\Response();
        $response->setBody([
            'title' => 'testTitle',
            'url' => '/topics/15',
            'articles' => '/articles/topic/15'
        ]);
        $this->assertEquals($response, $result);
    }

    public function testInsertTopic()
    {
        $mockPdo = new MockPDO();

        $app = $this->bootstrapApp('/topics', 'PUT', ['request' => ['source' => JSON_DIR . '/createTopic.json']]);
        $di = \BKTest\Models\Container::getInstance();
        $di->set('pdoDriver', $mockPdo);

        $this->stub(MockStatement::class, 'execute', [[
            ':title' => 'The Importance of Being Frank'
        ]], true);

        $this->stub(
            MockPDO::class,
            'prepare',
            ['INSERT INTO topic (title) VALUES (:title)'],
            new MockStatement()
        );

        $this->stub(MockPDO::class, 'lastInsertId', [], 99);

        $result = $app->run();

        $response = new \BKTest\Models\Response();
        $response->setBody([
            'title' => 'The Importance of Being Frank',
            'url' => '/topics/99',
            'articles' => '/articles/topic/99'
        ]);
        $this->assertEquals($response, $result);
    }

    public function testDeleteTopic()
    {
        $mockPdo = new MockPDO();

        $app = $this->bootstrapApp('/topics/900', 'DELETE');
        $di = \BKTest\Models\Container::getInstance();
        $di->set('pdoDriver', $mockPdo);

        $this->stub(MockPDO::class, 'query', ['DELETE FROM topic WHERE id = 900;'], true);

        $result = $app->run();

        $response = new \BKTest\Models\Response();
        $response->setBody(true);
        $this->assertEquals($response, $result);
    }

    public function testListTopics()
    {
        $mockPdo = new MockPDO();

        $app = $this->bootstrapApp('/topics', 'GET');
        $di = \BKTest\Models\Container::getInstance();
        $di->set('pdoDriver', $mockPdo);

        $this->stub(MockPDO::class, 'query', ['SELECT id, title FROM topic;'], new MockStatement());
        $this->stub(MockStatement::class, 'fetchAll', [\PDO::FETCH_ASSOC], [
            [
                'title' => 'Test title of Topic',
                'id' => '1111',
            ],
            [
                'title' => 'Test title of another Topic',
                'id' => '321',
            ],
        ]);

        $result = $app->run();

        $response = new \BKTest\Models\Response();
        $response->setBody([
            [
                'title' => 'Test title of Topic',
                'url' => '/topics/1111',
                'articles' => '/articles/topic/1111',
            ],
            [
                'title' => 'Test title of another Topic',
                'url' => '/topics/321',
                'articles' => '/articles/topic/321',
            ]
        ]);
        $this->assertEquals($response, $result);
    }
}

