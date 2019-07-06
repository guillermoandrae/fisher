<?php

namespace Guillermoandrae\Fisher\Db\DynamoDb;

final class ScanRequest extends AbstractLimitableRequest
{
    /**
     * @var boolean Whether or not to scan forward.
     */
    private $scanIndexForward = false;

    /**
     * @var boolean Whether or not the read should be consistent.
     */
    private $consistentRead = false;

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
            'ReturnConsumedCapacity' => $this->returnConsumedCapacity,
        ];
        if ($this->limit) {
            $query['Limit'] = $this->limit;
        }
        if ($this->filterExpression) {
            $query['FilterExpression'] = $this->filterExpression;
            $query['ExpressionAttributeValues'] = $this->expressionAttributeValues;
        }
        return $query;
    }
}
