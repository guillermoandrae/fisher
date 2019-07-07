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

    public function testFilterExpressionGT()
    {
        $expectedExpression = 'width > :width';
        $this->request->setFilterExpression([
            'width' => [
                'operator' => RequestOperators::GT,
                'value' => '10',
            ]
        ]);
        $this->assertEquals($expectedExpression, $this->request->toArray()['FilterExpression']);
    }

    public function testFilterExpressionGTE()
    {
        $expectedExpression = 'width >= :width';
        $this->request->setFilterExpression([
            'width' => [
                'operator' => RequestOperators::GTE,
                'value' => '10',
            ]
        ]);
        $this->assertEquals($expectedExpression, $this->request->toArray()['FilterExpression']);
    }

    public function testFilterExpressionLT()
    {
        $expectedExpression = 'width < :width';
        $this->request->setFilterExpression([
            'width' => [
                'operator' => RequestOperators::LT,
                'value' => '10',
            ]
        ]);
        $this->assertEquals($expectedExpression, $this->request->toArray()['FilterExpression']);
    }

    public function testFilterExpressionLTE()
    {
        $expectedExpression = 'width <= :width';
        $this->request->setFilterExpression([
            'width' => [
                'operator' => RequestOperators::LTE,
                'value' => '10',
            ]
        ]);
        $this->assertEquals($expectedExpression, $this->request->toArray()['FilterExpression']);
    }

    public function testSetFilterExpressionAndExpressionAttributeValues()
    {
        $this->request->setFilterExpression([
            'color' => [
                'operator' => RequestOperators::EQ,
                'value' => 'black',
            ],
            'shape' => [
                'operator' => RequestOperators::CONTAINS,
                'value' => 'square'
            ],
            'width' => [
                'operator' => RequestOperators::GTE,
                'value' => 10
            ]
        ]);
        $expectedQuery = [
            'TableName' => 'test',
            'ReturnConsumedCapacity' => 'NONE',
            'FilterExpression' => 'color = :color and contains(shape, :shape) and width >= :width',
            'ExpressionAttributeValues' => [
                ':color' => [
                    'S' => 'black'
                ],
                ':shape' => [
                    'S' => 'square',
                ],
                ':width' => [
                    'S' => 10
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
