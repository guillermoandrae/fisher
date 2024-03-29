<?php

namespace App\Repositories;

use App\Contracts\AbstractDynamoDbRepository;
use Guillermoandrae\DynamoDb\Constant\Operators;
use Guillermoandrae\Models\ModelInterface;

final class PostsRepository extends AbstractDynamoDbRepository
{
    protected string $tableName = 'social-posts';

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
}
