<?php

namespace GuillermoandraeTest\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use PHPUnit\Framework\TestCase;
use Guillermoandrae\Fisher\Db\DbException;
use Guillermoandrae\Fisher\Db\DynamoDb\DynamoDbAdapter;
use GuillermoandraeTest\Fisher\LocalDynamoDbClient;

final class DynamoDbAdapterTest extends TestCase
{
    private $adapter;
    
    public function testBadFindAll()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->findAll();
    }

    public function testBadFindLatest()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->findLatest();
    }

    public function testBadInsert()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->insert([]);
    }

    public function testBadDelete()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->delete([]);
    }

    protected function setUp(): void
    {
        $dynamoDb = LocalDynamoDbClient::get();
        $this->adapter = new DynamoDbAdapter($dynamoDb, new Marshaler());
    }
}
