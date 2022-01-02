<?php

namespace AppTest\Contracts;

use BadMethodCallException;
use App\Repositories\PostsRepository;
use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;
use Guillermoandrae\DynamoDb\Contract\DynamoDbAdapterInterface;
use Guillermoandrae\DynamoDb\DynamoDbAdapter;
use Guillermoandrae\Models\InvalidModelException;
use PHPUnit\Framework\TestCase;

final class RepositoryTest extends TestCase
{
    private DynamoDbAdapterInterface $dynamoDbAdapter;

    private string $tableName = 'social-posts';

    public function testInvalidModel(): void
    {
        $this->expectException(InvalidModelException::class);
        $repository = new PostsRepository($this->dynamoDbAdapter);
        $repository->setModelName('ThisIsAThing');
        $repository->find(['id' => 'bang']);
    }

    public function testBadMethodCall(): void
    {
        $this->expectException(BadMethodCallException::class);
        $repository = new PostsRepository($this->dynamoDbAdapter);
        $repository->update(['id' => 'bang'], []);
    }

    protected function setUp(): void
    {
        $this->dynamoDbAdapter = new DynamoDbAdapter();
        $this->dynamoDbAdapter->createTable([
            'id' => [AttributeTypes::STRING, KeyTypes::HASH]
        ], $this->tableName);
    }

    protected function tearDown(): void
    {
        $this->dynamoDbAdapter->deleteTable($this->tableName);
    }
}
