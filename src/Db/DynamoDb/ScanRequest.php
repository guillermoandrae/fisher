<?php

namespace Guillermoandrae\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;

final class ScanRequest extends AbstractItemRequest
{
    use LimitableRequestTrait;

    /**
     * @var boolean Whether or not to scan forward.
     */
    private $scanIndexForward = false;

    /**
     * @var boolean Whether or not the read should be consistent.
     */
    private $consistentRead = false;

    /**
     * Registers the JSON Marshaler and table name with this object.
     *
     * @param Marshaler $marshaler The JSON Marshaler.
     * @param string $tableName The table name.
     */
    public function __construct(Marshaler $marshaler, string $tableName)
    {
        parent::__construct($marshaler);
        $this->setTableName($tableName);
    }

    /**
     * Registers the ScanIndexForward value with this object.
     *
     * @param boolean $scanIndexForward Whether or not to scan forward.
     * @return ScanRequest This object.
     */
    public function setScanIndexForward(bool $scanIndexForward): ScanRequest
    {
        $this->scanIndexForward = $scanIndexForward;
        return $this;
    }

    /**
     * Registers the ConsistentRead value with this object.
     *
     * @param boolean $consistentRead Whether or not the read should be consistent.
     * @return ScanRequest This object.
     */
    public function setConsistentRead(bool $consistentRead): ScanRequest
    {
        $this->consistentRead = $consistentRead;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
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
