<?php

namespace Guillermoandrae\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;

abstract class AbstractLimitableRequest extends AbstractItemRequest
{
    /**
     * @var string The filter expression.
     */
    protected $filterExpression;
    
    /**
     * @var int The result limit.
     */
    protected $limit;

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
     * Registers the result limit with this object.
     *
     * @param integer $limit The result limit.
     * @return AbstractLimitableRequest An implementation of this abstract.
     */
    final public function setLimit(int $limit): AbstractLimitableRequest
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Registers the filter expression with this object.
     *
     * @param array $data The filter expression data.
     * @return AbstractLimitableRequest An implementation of this abstract.
     */
    final public function setFilterExpression(array $data): AbstractLimitableRequest
    {
        $filterExpressionArray = [];
        foreach ($data as $key => $options) {
            $filterExpressionArray[] = $this->parseExpression($options['operator'], $key);
            $this->addExpressionAttributeValue($key, $options['value']);
        }
        $this->filterExpression = implode(' and ', $filterExpressionArray);
        return $this;
    }

    protected function parseExpression(string $operator, $key): string
    {
        switch ($operator) {
            case RequestOperators::GT:
                return sprintf('%s > :%s', $key, $key);
            case RequestOperators::GTE:
                return sprintf('%s >= :%s', $key, $key);
            case RequestOperators::LT:
                return sprintf('%s < :%s', $key, $key);
            case RequestOperators::LTE:
                return sprintf('%s <= :%s', $key, $key);
            case RequestOperators::CONTAINS:
                return sprintf('contains(%s, :%s)', $key, $key);
            case RequestOperators::EQ:
            default:
                return sprintf('%s = :%s', $key, $key);
        }
    }
}
