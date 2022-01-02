<?php

namespace App\Repositories;

use App\Contracts\AbstractDynamoDbRepository;
use Guillermoandrae\DynamoDb\Constant\Operators;
use Guillermoandrae\Models\ModelInterface;

final class BlackQuotesRepository extends AbstractDynamoDbRepository
{
    protected string $tableName = 'black-quotes';

    public function create(array $data): ModelInterface
    {
        $this->adapter->useTable($this->tableName)->insert($data);
        $results = $this->findWhere([
            'partition' => [
                'name' => 'author',
                'value' => $data['author']
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
