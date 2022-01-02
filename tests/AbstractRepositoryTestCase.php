<?php

namespace AppTest;

use App\Contracts\DynamoDbRepositoryInterface;
use Guillermoandrae\DynamoDb\Contract\DynamoDbAdapterInterface;
use Guillermoandrae\DynamoDb\DynamoDbAdapter;
use Guillermoandrae\Repositories\RepositoryFactory;
use ICanBoogie\Inflector;
use PHPUnit\Framework\TestCase;

abstract class AbstractRepositoryTestCase extends TestCase
{
    protected string $tableName = '';

    protected string $modelName = '';

    protected DynamoDbAdapterInterface $dynamoDbAdapter;

    protected DynamoDbRepositoryInterface $repository;

    final public function testGetProperties(): void
    {
        $this->assertEquals($this->tableName, $this->repository->getTableName());
        $this->assertEquals(
            Inflector::get()->singularize(ucfirst($this->modelName)) . 'Model',
            explode('\\', $this->repository->getModelName())[2]
        );
    }

    abstract public function testCreate(): void;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dynamoDbAdapter = new DynamoDbAdapter();
        if (!$this->dynamoDbAdapter->tableExists($this->tableName)) {
            $this->setUpTable();
        }
        $this->setUpRepository();
    }

    protected function setUpRepository(): void
    {
        /** @var DynamoDbRepositoryInterface $repository */
        $repository = RepositoryFactory::factory(
            Inflector::get()->pluralize($this->modelName),
            $this->dynamoDbAdapter
        );
        $this->repository = $repository;
    }

    abstract protected function setUpTable(): void;

    protected function tearDown(): void
    {
        $this->dynamoDbAdapter->deleteTable($this->tableName);
    }
}
