<?php

namespace App\Repositories;

use App\Contracts\AbstractDynamoDbRepository;
use Guillermoandrae\DynamoDb\Constant\Operators;
use Guillermoandrae\Models\ModelInterface;

final class SongsRepository extends AbstractDynamoDbRepository
{
    protected string $tableName = 'songs';

    public function create(array $data): ModelInterface
    {
        $this->adapter->useTable($this->tableName)->insert($data);
        $results = $this->findWhere([
            'partition' => [
                'name' => 'Artist',
                'value' => $data['Artist']
            ],
            'sort' => [
                'name' => 'SongTitle',
                'operator' => Operators::EQ,
                'value' => $data['SongTitle']
            ]
        ]);
        return $results[0];
    }
}
