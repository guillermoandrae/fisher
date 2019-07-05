<?php

namespace Guillermoandrae\Fisher\Db\DynamoDb;

trait LimitableRequestTrait
{
    protected $filterExpression;
        
    protected $limit;

    final public function setFilterExpression(array $data)
    {
        $filterExpressionArray = [];
        foreach ($data as $key => $options) {
            $filterExpressionArray[] = $this->parseExpression($options['operator'], $key);
            $this->addExpressionAttributeValue($key, $options['value']);
        }
        $this->filterExpression = implode(' and ', $filterExpressionArray);
        return $this;
    }

    final public function setLimit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    protected function parseExpression($operator, $key): string
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
