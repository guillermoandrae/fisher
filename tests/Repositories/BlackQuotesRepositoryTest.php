<?php

namespace AppTest\Repositories;

use App\Models\BlackQuoteModel;
use App\Repositories\BlackQuotesRepository;
use AppTest\AbstractRepositoryTestCase;
use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;

final class BlackQuotesRepositoryTest extends AbstractRepositoryTestCase
{
    protected string $tableName = 'black-quotes';

    protected string $modelName = 'blackQuote';

    public function testCreate(): void
    {
        $data = [
            'author' => 'James Baldwin',
            'createdAt' => strtotime('today'),
            'quote' => 'Love does not begin and end the way we seem to think it does.'
        ];

        /** @var BlackQuoteModel $result */
        $result = $this->repository->create($data);
        $this->assertEquals($data['author'], $result->getAuthor());
        $this->assertEquals($data['createdAt'], $result->getCreatedAt());
        $this->assertEquals($data['quote'], $result->getQuote());
        unset($data['quote']);
        $this->repository->delete($data);
    }

    protected function setUpRepository(): void
    {
        $this->repository = new BlackQuotesRepository($this->dynamoDbAdapter);
    }

    protected function setUpTable(): void
    {
        $this->dynamoDbAdapter->createTable([
            'author' => [AttributeTypes::STRING, KeyTypes::HASH],
            'createdAt' => [AttributeTypes::NUMBER, KeyTypes::RANGE],
        ], $this->tableName);
    }
}
