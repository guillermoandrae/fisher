<?php

namespace GuillermoandraeTest\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use PHPUnit\Framework\TestCase;
use Aws\DynamoDb\DynamoDbClient;
use Guillermoandrae\Fisher\Db\DbException;
use GuillermoandraeTest\Fisher\LocalDynamoDbClient;
use Guillermoandrae\Fisher\Db\DynamoDb\DynamoDbAdapter;

final class DynamoDbAdapterTest extends TestCase
{
    /**
     * @var DynamoDbAdapter
     */
    private $adapter;

    public function testCreateDeleteListTable()
    {
        $this->adapter->useTable('widgets')->createTable([
            'name' => ['type' => 'S', 'keyType' => 'HASH'],
            'date' => ['type' => 'N', 'keyType' => 'RANGE'],
        ]);
        $this->assertContains('widgets', $this->adapter->listTables());
        $this->adapter->useTable('widgets')->deleteTable();
        $this->assertNotContains('widgets', $this->adapter->listTables());
    }
    
    public function testBadCreateTable()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('te\st')->createTable(['name' => ['type' => 'S', 'keyType' => 'HASH']]);
    }

    public function testBadCreateTableBadKeySchema()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->createTable([]);
    }

    public function testBadDeleteTable()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->deleteTable([]);
    }

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

    public function testBadFindById()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->findById([]);
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

    public function testGetClient()
    {
        $this->assertInstanceOf(DynamoDbClient::class, $this->adapter->getClient());
    }

    protected function setUp(): void
    {
        $dynamoDb = LocalDynamoDbClient::get();
        $this->adapter = new DynamoDbAdapter($dynamoDb, new Marshaler());
    }
}
