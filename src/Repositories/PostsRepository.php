<?php

namespace App\Repositories;

use App\Models\PostModel;
use BadMethodCallException;
use Guillermoandrae\Common\Collection;
use Guillermoandrae\Common\CollectionInterface;
use Guillermoandrae\DynamoDb\Constant\Operators;
use Guillermoandrae\Models\ModelInterface;

final class PostsRepository extends AbstractRepository
{
    public const DEFAULT_LIMIT = 25;

    private string $tableName = 'social-posts';

    public function findAll(int $offset = 0, ?int $limit = null): CollectionInterface
    {
        $posts = [];
        $results = $this->adapter->useTable($this->tableName)->findAll();
        foreach ($results as $post) {
            $posts[] = new PostModel($post);
        }
        return Collection::make($posts)->sortBy('createdAt', true)
            ->limit($offset, $limit ?? self::DEFAULT_LIMIT);
    }

    public function find(mixed $primaryKey): ModelInterface
    {
        $results = $this->adapter->useTable($this->tableName)->find($primaryKey);
        return new PostModel($results);
    }

    public function findWhere(array $where, int $offset = 0, ?int $limit = null): CollectionInterface
    {
        $posts = [];
        $results = $this->adapter->useTable($this->tableName)
            ->findWhere($where, $offset, $limit ?? self::DEFAULT_LIMIT);
        foreach ($results as $post) {
            $posts[] = new PostModel($post);
        }
        return Collection::make($posts);
    }

    public function create(array $data): ModelInterface
    {
        $this->adapter->useTable($this->tableName)->insert($data);
        $results = $this->findWhere([
            'partition' => [
                'name' => 'originalAuthor',
                'value' => $data['originalAuthor']
            ],
            'sort' => [
                'name' => 'createdAt',
                'operator' => Operators::EQ,
                'value' => $data['createdAt']
            ]
        ]);
        return $results[0];
    }

    public function update(mixed $primaryKey, array $data): ModelInterface
    {
        throw new BadMethodCallException(sprintf('The %s method has not been implemented.', 'update'));
    }

    public function delete(mixed $primaryKey): bool
    {
        return $this->adapter->useTable($this->tableName)->delete($primaryKey);
    }
}
