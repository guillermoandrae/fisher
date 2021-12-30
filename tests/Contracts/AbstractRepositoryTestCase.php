<?php

namespace AppTest\Contracts;

use Guillermoandrae\DynamoDb\Contract\DynamoDbAdapterInterface;
use Guillermoandrae\DynamoDb\DynamoDbAdapter;
use Guillermoandrae\Repositories\RepositoryFactory;
use Guillermoandrae\Repositories\RepositoryInterface;
use ICanBoogie\Inflector;
use PHPUnit\Framework\TestCase;

abstract class AbstractRepositoryTestCase extends TestCase
{
    protected string $tableName = '';

    protected string $modelName = '';

    protected DynamoDbAdapterInterface $dynamoDbAdapter;

    protected RepositoryInterface $repository;

    abstract public function testCreate(): void;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dynamoDbAdapter = new DynamoDbAdapter();
        if (!$this->dynamoDbAdapter->tableExists($this->tableName)) {
            $this->setUpTable();
        }
        $this->repository = RepositoryFactory::factory(
            Inflector::get()->pluralize($this->modelName),
            $this->dynamoDbAdapter
        );
    }

    abstract protected function setUpTable(): void;

    protected function tearDown(): void
    {
        $this->dynamoDbAdapter->deleteTable($this->tableName);
    }
}
