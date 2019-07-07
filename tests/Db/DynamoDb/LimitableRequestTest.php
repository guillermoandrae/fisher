<?php

namespace GuillermoandraeTest\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use PHPUnit\Framework\TestCase;
use Guillermoandrae\Fisher\Db\DynamoDb\AbstractLimitableRequest;
use Guillermoandrae\Fisher\Db\DynamoDb\RequestOperators;

final class LimitableRequestTest extends TestCase
{
    /**
     * @var AbstractLimitableRequest The request.
     */
    private $request;

    public function testSetFilterExpression()
    {
        $this->request->setFilterExpression([
            'color' => [
                'operator' => RequestOperators::EQ,
                'value' => 'black',
            ],
            'shape' => [
                'operator' => RequestOperators::CONTAINS,
                'value' => 'square'
            ]
        ]);
        $expectedQuery = [
            'TableName' => 'test',
            'ReturnConsumedCapacity' => 'NONE',
            'FilterExpression' => 'color = :color and contains(shape, :shape)',
            'ExpressionAttributeValues' => [
                ':color' => [
                    'S' => 'black'
                ],
                ':shape' => [
                    'S' => 'square',
                ]
            ],
        ];
        $this->assertEquals($expectedQuery, $this->request->toArray());
    }
    
    public function testSetReturnConsumedCapacity()
    {
        $this->request->setReturnConsumedCapacity('INDEXES');
        $expectedQuery = [
            'TableName' => 'test',
            'ReturnConsumedCapacity' => 'INDEXES'
        ];
        $this->assertEquals($expectedQuery, $this->request->toArray());
    }

    public function testSetLimit()
    {
        $this->request->setLimit(2);
        $expectedQuery = [
            'TableName' => 'test',
            'ReturnConsumedCapacity' => 'NONE',
            'Limit' => 2
        ];
        $this->assertEquals($expectedQuery, $this->request->toArray());
    }

    protected function setUp(): void
    {
        $this->request = $this->getMockForAbstractClass(
            AbstractLimitableRequest::class,
            [new Marshaler(), 'test']
        );
    }

    protected function tearDown(): void
    {
        $this->request = null;
    }
}
