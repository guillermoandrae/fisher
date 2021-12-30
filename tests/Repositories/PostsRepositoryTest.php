<?php

namespace AppTest\Repositories;

use AppTest\Contracts\AbstractRepositoryTestCase;
use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;
use Guillermoandrae\DynamoDb\Constant\Operators;

final class PostsRepositoryTest extends AbstractRepositoryTestCase
{
    protected string $tableName = 'social-posts';

    protected string $modelName = 'posts';

    public function testCreate(): void
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

    public function testFind(): void
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

    public function testFindAllAndDelete(): void
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

    public function testFindWhere(): void
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

    protected function setUpTable(): void
    {
        $this->dynamoDbAdapter->createTable([
            'originalAuthor' => [AttributeTypes::STRING, KeyTypes::HASH],
            'createdAt' => [AttributeTypes::NUMBER, KeyTypes::RANGE],
        ], $this->tableName);
    }
}
