<?php

/**
 * Created by PhpStorm.
 * User: Brian
 * Date: 24/08/2015
 * Time: 23:00
 */
class ArticleTest extends TestCase
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

    public function testSelectArticle()
    {
        $mockPdo = new MockPDO();

        $app = $this->bootstrapApp('/articles/15', 'GET');
        $di = \BKTest\Models\Container::getInstance();
        $di->set('pdoDriver', $mockPdo);

        $dbResult = [
            'title' => 'testTitle',
            'author' => 'testAuthor',
            'content' => 'testContent',
            'id' => '15',
            'topic' => '1001'
        ];

        $this->stub(MockStatement::class, 'fetch', [PDO::FETCH_ASSOC], $dbResult);

        $this->stub(
            MockPDO::class,
            'query',
            ['SELECT id, title, author, content, topic FROM article WHERE id = 15;'],
            new MockStatement()
        );

        $result = $app->run();

        $response = new \BKTest\Models\Response();
        $response->setBody([
            'title' => 'testTitle',
            'author' => 'testAuthor',
            'content' => 'testContent',
            'url' => '/articles/15',
            'topic' => '/topics/1001'
        ]);
        $this->assertEquals($response, $result);
    }

    public function testInsertArticle()
    {
        $mockPdo = new MockPDO();

        $app = $this->bootstrapApp('/articles', 'PUT', ['request' => ['source' => JSON_DIR . '/createArticle.json']]);
        $di = \BKTest\Models\Container::getInstance();
        $di->set('pdoDriver', $mockPdo);

        $this->stub(MockStatement::class, 'execute', [[
            ':title' => 'Some Article title',
            ':author' => 'W. H. Auden',
            ':content' => 'Lorem Ipsum something something, I forget',
            ':topic' => 765,
        ]], true);

        $this->stub(
            MockPDO::class,
            'prepare',
            ['INSERT INTO article (title, author, content, topic) VALUES (:title, :author, :content, :topic);'],
            new MockStatement()
        );

        $this->stub(MockPDO::class, 'lastInsertId', [], 100);

        $result = $app->run();

        $response = new \BKTest\Models\Response();
        $response->setBody([
            'title' => 'Some Article title',
            'author' => 'W. H. Auden',
            'content' => 'Lorem Ipsum something something, I forget',
            'url' => '/articles/100',
            'topic' => '/topics/765'
        ]);
        $this->assertEquals($response, $result);
    }

    public function testDeleteArticle()
    {
        $mockPdo = new MockPDO();

        $app = $this->bootstrapApp('/articles/222', 'DELETE');
        $di = \BKTest\Models\Container::getInstance();
        $di->set('pdoDriver', $mockPdo);

        $this->stub(MockPDO::class, 'query', ['DELETE FROM article WHERE id = 222'], true);

        $result = $app->run();

        $response = new \BKTest\Models\Response();
        $response->setBody(true);
        $this->assertEquals($response, $result);
    }

    public function testListArticles()
    {
        $mockPdo = new MockPDO();

        $app = $this->bootstrapApp('/articles/topic/101', 'GET');
        $di = \BKTest\Models\Container::getInstance();
        $di->set('pdoDriver', $mockPdo);

        $this->stub(MockPDO::class, 'quote', ['101'], '201');
        $this->stub(MockPDO::class, 'query', ['SELECT id, title, author, content, topic FROM article WHERE topic = 201'], new MockStatement());
        $this->stub(MockStatement::class, 'fetchAll', [\PDO::FETCH_ASSOC], [
            [
                'title' => 'Test title',
                'author' => 'Test author',
                'content' => 'Test content',
                'id' => 'could be a string',
                'topic' => 'why not zoidberg?'
            ]
        ]);

        $result = $app->run();

        $response = new \BKTest\Models\Response();
        $response->setBody([
            [
                'title' => 'Test title',
                'author' => 'Test author',
                'content' => 'Test content',
                'url' => '/articles/could be a string',
                'topic' => '/topics/why not zoidberg?',
            ]
        ]);
        $this->assertEquals($response, $result);
    }
}

class MockStatement
{
    static $calls = [];
    static $returns = [];

    public function stub($function, $args)
    {
        self::$calls[] = [$function => $args];

        if (isset(self::$returns[$function])) {
            return array_shift(self::$returns[$function]);
        }
    }

    public function fetch()
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function execute()
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function __call($name, $args)
    {
        return $this->stub($name, $args);
    }
}