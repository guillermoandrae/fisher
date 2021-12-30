<?php

namespace AppTest\Repositories;

use AppTest\Contracts\AbstractRepositoryTestCase;
use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;

final class SongsRepositoryTest extends AbstractRepositoryTestCase
{
    protected string $tableName = 'songs';

    protected string $modelName = 'songs';

    public function testCreate(): void
    {
        $data = [
            'Artist' => 'Common',
            'SongTitle' => 'Resurrection'
        ];
        $result = $this->repository->create($data);
        $this->assertEquals($data['Artist'], $result->getArtist());
        $this->assertEquals($data['SongTitle'], $result->getSongTitle());
        $this->repository->delete($data);
    }

    protected function setUpTable(): void
    {
        $this->dynamoDbAdapter->createTable([
            'Artist' => [AttributeTypes::STRING, KeyTypes::HASH],
            'SongTitle' => [AttributeTypes::STRING, KeyTypes::RANGE],
        ], $this->tableName);
    }
}
