<?php

namespace AppTest\Repositories;

use BadMethodCallException;
use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;
use Guillermoandrae\DynamoDb\Constant\Operators;
use Guillermoandrae\DynamoDb\Contract\DynamoDbAdapterInterface;
use Guillermoandrae\DynamoDb\DynamoDbAdapter;
use Guillermoandrae\Repositories\RepositoryFactory;
use Guillermoandrae\Repositories\RepositoryInterface;
use PHPUnit\Framework\TestCase;

final class PostsRepositoryTest extends TestCase
{
    private string $tableName = 'social-posts';

    private DynamoDbAdapterInterface $dynamoDbAdapter;

    private RepositoryInterface $repository;

    public function testCreate()
    {
        $data = [
            'originalAuthor' => 'testing',
            'createdAt' => strtotime('today')
        ];
        $result = $this->repository->create($data);
        $this->assertEquals($data['originalAuthor'], $result->getOriginalAuthor());
        $this->assertEquals($data['createdAt'], $result->getCreatedAt());
        $this->repository->delete($data);
    }

    public function testFind()
    {
        $data = [
            'originalAuthor' => 'testing',
            'createdAt' => strtotime('today')
        ];
        $this->repository->create($data);
        $result = $this->repository->find($data);
        $this->assertEquals($data['originalAuthor'], $result->getOriginalAuthor());
        $this->assertEquals($data['createdAt'], $result->getCreatedAt());
        $this->repository->delete($data);
    }

    public function testFindAllAndDelete()
    {
        $data = [
            'originalAuthor' => 'testing',
            'createdAt' => strtotime('today')
        ];
        $this->repository->create($data);
        $posts = $this->repository->findAll();
        $this->assertCount(1, $posts);
        $this->repository->delete($data);
        $posts = $this->repository->findAll();
        $this->assertCount(0, $posts);
    }

    public function testFindWhere()
    {
        $data = [
            ['originalAuthor' => 'testing1', 'createdAt' => strtotime('yesterday')],
            ['originalAuthor' => 'testing1', 'createdAt' => strtotime('today')],
            ['originalAuthor' => 'testing2', 'createdAt' => strtotime('today')]
        ];
        foreach ($data as $datum) {
            $this->repository->create($datum);
        }
        $posts = $this->repository->findWhere([
            'partition' => [
                'name' => 'originalAuthor',
                'value' => 'testing1'
            ],
            'sort' => [
                'name' => 'createdAt',
                'operator' => Operators::LT,
                'value' => strtotime('today')
            ]
        ]);
        $this->assertCount(1, $posts);
        foreach ($data as $datum) {
            $this->repository->delete($datum);
        }
    }

    public function testUpdate()
    {
        $this->expectException(BadMethodCallException::class);
        $this->repository->update(1, []);
    }

    protected function setUp(): void
    {
        $this->dynamoDbAdapter = new DynamoDbAdapter();
        if (!$this->dynamoDbAdapter->tableExists($this->tableName)) {
            $this->dynamoDbAdapter->createTable([
                'originalAuthor' => [AttributeTypes::STRING, KeyTypes::HASH],
                'createdAt' => [AttributeTypes::NUMBER, KeyTypes::RANGE],
            ], $this->tableName);
        }
        $this->repository = RepositoryFactory::factory('posts', $this->dynamoDbAdapter);
    }

    protected function tearDown(): void
    {
        $this->dynamoDbAdapter->deleteTable($this->tableName);
    }
}
