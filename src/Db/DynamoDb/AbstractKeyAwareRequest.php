<?php

namespace Guillermoandrae\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;

abstract class AbstractKeyAwareRequest extends AbstractItemRequest
{
    protected $key;
    
    public function __construct(Marshaler $marshaler, string $tableName, array $key)
    {
        parent::__construct($marshaler);
        $this->setTableName($tableName);
        $this->setKey($key);
    }

    public function setKey(array $key): AbstractKeyAwareRequest
    {
        $this->key = $this->marshaler->marshalItem($key);
        return $this;
    }

    public function get(): array
    {
        return [
            'TableName' => $this->tableName,
            'Key' => $this->key
        ];
    }
}
