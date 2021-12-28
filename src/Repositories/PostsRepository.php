<?php

namespace Guillermoandrae\Fisher\Repositories;

use Guillermoandrae\Common\Collection;
use Guillermoandrae\Models\ModelInterface;
use Guillermoandrae\Fisher\Models\PostModel;
use Guillermoandrae\Common\CollectionInterface;

final class PostsRepository extends AbstractRepository
{
    /**
     * @var integer The default row limit.
     */
    const DEFAULT_LIMIT = 25;

    /**
     * @var string
     */
    private string $tableName = 'social-media-posts';
    
    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function find(mixed $id): ModelInterface
    {
        return $this->findById($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findById(mixed $id): ModelInterface
    {
        $results = $this->adapter->useTable($this->tableName)->findById($id);
        return new PostModel($results);
    }

    /**
     * {@inheritDoc}
     */
    public function findWhere(array $where, int $offset = 0, ?int $limit = null): CollectionInterface
    {
        throw new \BadMethodCallException(sprintf('The %s method has not been implemented.', 'findWhere'));
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): ModelInterface
    {
        $this->adapter->useTable($this->tableName)->insert($data);
        $result = $this->adapter->useTable($this->tableName)->findLatest();
        return new PostModel($result);
    }

    /**
     * {@inheritDoc}
     */
    public function update(mixed $id, array $data): ModelInterface
    {
        throw new \BadMethodCallException(sprintf('The %s method has not been implemented.', 'update'));
    }

    /**
     * {@inheritDoc}
     */
    public function delete(mixed $id): bool
    {
        return $this->adapter->useTable($this->tableName)->delete($id);
    }
}
