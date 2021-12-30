<?php

namespace App\Repositories;

use Guillermoandrae\DynamoDb\Contract\DynamoDbAdapterInterface;
use Guillermoandrae\Repositories\AbstractRepository as AbstractBaseRepository;

abstract class AbstractRepository extends AbstractBaseRepository
{
    protected DynamoDbAdapterInterface $adapter;

    final public function __construct(DynamoDbAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
}
