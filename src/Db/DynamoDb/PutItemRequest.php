<?php

namespace Guillermoandrae\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;

final class PutItemRequest extends AbstractItemRequest
{
    private $item;
    
    public function __construct(Marshaler $marshaler, string $tableName, array $item)
    {
        parent::__construct($marshaler);
        $this->setTableName($tableName);
        $this->setItem($item);
    }

    public function setItem(array $item): PutItemRequest
    {
        $this->item = $this->marshaler->marshalItem($item);
        return $this;
    }

    public function get(): array
    {
        return [
            'TableName' => $this->tableName,
            'Item' => $this->item,
        ];
    }
}
