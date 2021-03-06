<?php

namespace Guillermoandrae\Fisher\Repositories;

use Guillermoandrae\Fisher\Db\AdapterInterface;
use Guillermoandrae\Repositories\AbstractRepository as AbstractBaseRepository;

abstract class AbstractRepository extends AbstractBaseRepository
{
    /**
     * @var AdapterInterface The database adapter.
     */
    protected $adapter;
    
    /**
     * Registers the adapter with this object.
     *
     * @param AdapterInterface $adapter The adapter.
     */
    final public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
}
