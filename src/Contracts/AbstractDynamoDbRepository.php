<?php

namespace App\Contracts;

use BadMethodCallException;
use Guillermoandrae\Common\Collection;
use Guillermoandrae\Common\CollectionInterface;
use Guillermoandrae\DynamoDb\Contract\DynamoDbAdapterInterface;
use Guillermoandrae\Models\InvalidModelException;
use Guillermoandrae\Models\ModelInterface;
use ICanBoogie\Inflector;
use ReflectionClass;
use ReflectionException;

abstract class AbstractDynamoDbRepository extends AbstractRepository implements DynamoDbRepositoryInterface
{
    protected DynamoDbAdapterInterface $adapter;

    protected string $tableName = '';

    protected string $modelName = '';

    final public function __construct(DynamoDbAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        if (!$this->modelName) {
            preg_match('/App\\\\Repositories\\\\(.*)Repository/', get_called_class(), $matches);
            $className = Inflector::get()->singularize($matches[1]);
            $modelName = sprintf('App\Models\%sModel', $className);
            $this->modelName = $modelName;
        }
    }

    public function findAll(int $offset = 0, ?int $limit = null): CollectionInterface
    {
        $posts = [];
        $results = $this->adapter->useTable($this->tableName)->findAll();
        foreach ($results as $post) {
            $posts[] = $this->buildModel($post);
        }
        return Collection::make($posts)->sortBy('createdAt', true)
            ->limit($offset, $limit ?? AbstractRepository::DEFAULT_LIMIT);
    }

    public function find(mixed $primaryKey): ModelInterface
    {
        $results = $this->adapter->useTable($this->tableName)->find($primaryKey);
        return $this->buildModel($results);
    }

    public function findWhere(array $where, int $offset = 0, ?int $limit = null): CollectionInterface
    {
        $posts = [];
        $results = $this->adapter->useTable($this->tableName)
            ->findWhere($where, $offset, $limit ?? self::DEFAULT_LIMIT);
        foreach ($results as $post) {
            $posts[] = $this->buildModel($post);
        }
        return Collection::make($posts);
    }

    public function update(mixed $primaryKey, array $data): ModelInterface
    {
        throw new BadMethodCallException(sprintf('The %s method has not been implemented.', 'update'));
    }

    final public function delete(mixed $primaryKey): bool
    {
        return $this->adapter->useTable($this->tableName)->delete($primaryKey);
    }

    final public function getTableName(): string
    {
        return $this->tableName;
    }

    final public function setModelName(string $modelName): static
    {
        $this->modelName = $modelName;
        return $this;
    }

    final public function getModelName(): string
    {
        return $this->modelName;
    }

    private function buildModel(mixed $data): ModelInterface
    {
        $modelName = $this->modelName;
        try {
            $reflectionClass = new ReflectionClass($modelName);
            return $reflectionClass->newInstance($data);
        } catch (ReflectionException $ex) {
            throw new InvalidModelException(sprintf('The %s model does not exist', $modelName));
        }
    }
}
