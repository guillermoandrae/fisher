<?php

namespace Guillermoandrae\Fisher\Db\DynamoDb;

trait LimitAwareRequestTrait
{
    /**
     * @var int The result limit.
     */
    protected $limit;

    /**
     * Registers the result limit with this object.
     *
     * @param integer $limit The result limit.
     * @return mixed An implementation of this trait.
     */
    final public function setLimit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }
}
