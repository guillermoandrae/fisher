<?php

namespace GuillermoandraeTest\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use PHPUnit\Framework\TestCase;
use Guillermoandrae\Fisher\Db\DynamoDb\ScanRequest;
use Guillermoandrae\Fisher\Db\DynamoDb\RequestOperators;

final class ScanRequestTest extends TestCase
{
    public function testSetFilterExpression()
    {
        $request = new ScanRequest(new Marshaler(), 'test');
        $request->setFilterExpression([
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
            'ScanIndexForward' => false,
            'ConsistentRead' => false,
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
        $this->assertEquals($expectedQuery, $request->toArray());
    }
    
    public function testSetReturnConsumedCapacity()
    {
        $request = new ScanRequest(new Marshaler(), 'test');
        $request->setReturnConsumedCapacity('INDEXES');
        $expectedQuery = [
            'TableName' => 'test',
            'ScanIndexForward' => false,
            'ConsistentRead' => false,
            'ReturnConsumedCapacity' => 'INDEXES'
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }

    public function testSetLimit()
    {
        $request = new ScanRequest(new Marshaler(), 'test');
        $request->setLimit(2);
        $expectedQuery = [
            'TableName' => 'test',
            'ScanIndexForward' => false,
            'ConsistentRead' => false,
            'ReturnConsumedCapacity' => 'NONE',
            'Limit' => 2
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }
    
    public function testSetScanIndexForward()
    {
        $request = new ScanRequest(new Marshaler(), 'test');
        $request->setScanIndexForward(true);
        $expectedQuery = [
            'TableName' => 'test',
            'ScanIndexForward' => true,
            'ConsistentRead' => false,
            'ReturnConsumedCapacity' => 'NONE'
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }

    public function testSetConsistentRead()
    {
        $request = new ScanRequest(new Marshaler(), 'test');
        $request->setConsistentRead(true);
        $expectedQuery = [
            'TableName' => 'test',
            'ScanIndexForward' => false,
            'ConsistentRead' => true,
            'ReturnConsumedCapacity' => 'NONE'
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }
}
