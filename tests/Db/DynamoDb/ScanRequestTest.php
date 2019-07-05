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
        $request->setConsistentRead(true)->setScanIndexForward(true);
        $expectedQuery = ['TableName' => 'test', 'ScanIndexForward' => true, 'ConsistentRead' => true];
        $this->assertSame($expectedQuery, $request->toArray());
    }
}
