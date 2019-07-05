<?php

namespace Guillermoandrae\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;

final class ScanRequest extends AbstractItemRequest
{
    use LimitableRequestTrait;

    private $scanIndexForward = false;

    private $consistentRead = false;

    public function __construct(Marshaler $marshaler, string $tableName)
    {
        parent::__construct($marshaler);
        $this->setTableName($tableName);
    }

    public function setScanIndexForward(bool $scanIndexForward): ScanRequest
    {
        $this->scanIndexForward = $scanIndexForward;
        return $this;
    }

    public function setConsistentRead(bool $consistentRead): ScanRequest
    {
        $this->consistentRead = $consistentRead;
        return $this;
    }

    public function get(): array
    {
        $query = [
            'TableName' => $this->tableName,
            'ScanIndexForward' => $this->scanIndexForward,
            'ConsistentRead' => $this->consistentRead,
        ];
        if ($this->limit) {
            $query['Limit'] = $this->limit;
        }
        return $query;
    }
}
