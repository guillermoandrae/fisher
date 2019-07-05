<?php

namespace GuillermoandraeTest\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use PHPUnit\Framework\TestCase;
use Guillermoandrae\Fisher\Db\DynamoDb\ScanRequest;

final class ScanRequestTest extends TestCase
{
    public function testToArray()
    {
        $request = new ScanRequest(new Marshaler(), 'test');
        $expectedQuery = ['TableName' => 'test', 'ScanIndexForward' => false, 'ConsistentRead' => false];
        $this->assertSame($expectedQuery, $request->toArray());
    }
}
