<?php

namespace GuillermoandraeTest\Fisher\Repositories;

use Aws\DynamoDb\Marshaler;
use PHPUnit\Framework\TestCase;
use Guillermoandrae\Repositories\RepositoryFactory;
use GuillermoandraeTest\Fisher\LocalDynamoDbClient;
use Guillermoandrae\Repositories\RepositoryInterface;
use Guillermoandrae\Fisher\Db\DynamoDb\DynamoDbAdapter;

final class PostsRepositoryTest extends TestCase
{
    private static $dynamoDb;

    private static $tableName = 'social-media-posts';
    
    private static $adapter;
    
    /**
     * @var RepositoryInterface
     */
    private $repository;

    public static function setUpBeforeClass(): void
    {
        self::$dynamoDb = LocalDynamoDbClient::get();
        self::$dynamoDb->createTable([
            'TableName' => self::$tableName,
            'KeySchema' => [
                [
                    'AttributeName' => 'source',
                    'KeyType' => 'HASH'
                ],
                [
                    'AttributeName' => 'createdAt',
                    'KeyType' => 'RANGE'
                ]
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'source',
                    'AttributeType' => 'S'
                ],
                [
                    'AttributeName' => 'createdAt',
                    'AttributeType' => 'N'
                ],
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 5,
                'WriteCapacityUnits' => 5,
            ],
        ]);
        self::$adapter = new DynamoDbAdapter(self::$dynamoDb, new Marshaler());
    }

    public static function tearDownAfterClass(): void
    {
        self::$dynamoDb->deleteTable(['TableName' => self::$tableName]);
    }
    
    public function testFindById()
    {
        $data = ['source' => 'Spotify', 'createdAt' => strtotime('today')];
        $result = $this->repository->create($data);
        $this->assertEquals($result, $this->repository->findById($data));
        $this->repository->delete($data);
    }

    public function testInsert()
    {
        $sources = ['Twitter', 'Instagram', 'Pinterest'];
        foreach ($sources as $source) {
            $data = ['source' => $source, 'createdAt' => strtotime('today')];
            $result = $this->repository->create($data);
            $this->assertSame($source, $result['source']);
        }
    }

    public function testDelete()
    {
        $this->repository->delete(['source' => 'Twitter', 'createdAt' => strtotime('today')]);
        $posts = $this->repository->findAll();
        $this->assertCount(2, $posts);
    }

    public function testFindWhere()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->repository->findWhere([]);
    }

    public function testUpdate()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->repository->update(1, []);
    }

    protected function setUp(): void
    {
        $this->repository = RepositoryFactory::factory('posts', self::$adapter);
    }
}
