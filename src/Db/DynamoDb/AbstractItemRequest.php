<?php

namespace Guillermoandrae\Fisher\Db\DynamoDb;

abstract class AbstractItemRequest extends AbstractTableAwareRequest
{
    /**
     * @var array
     */
    protected $expressionAttributeValues = [];
    
    /**
     * @var string
     */
    protected $returnConsumedCapacity = ConsumedCapacityOptions::NONE;

    /**
     * Adds an ExpressionAttributeValue to the request.
     *
     * @param string $key The attribute token.
     * @param string $value The attribute value.
     * @return AbstractItemRequest An implementation of this abstract.
     */
    final public function addExpressionAttributeValue(string $key, string $value): AbstractItemRequest
    {
        $this->expressionAttributeValues[sprintf(':%s', $key)] = $this->marshaler->marshalValue($value);
        return $this;
    }

    /**
     * Sets the desired level of consumption detail to return.
     *
     * @param string $returnConsumedCapacity The level of consumption detail to return.
     * @return AbstractItemRequest An implementation of this abstract.
     */
    final public function setReturnConsumedCapacity(string $returnConsumedCapacity): AbstractItemRequest
    {
        $this->returnConsumedCapacity = $returnConsumedCapacity;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): array
    {
        $query = parent::get();
        if ($this->expressionAttributeValues) {
            $query['ExpressionAttributeValues'] = $this->expressionAttributeValues;
        }
        $query['ReturnConsumedCapacity'] = $this->returnConsumedCapacity;
        return $query;
    }
}
