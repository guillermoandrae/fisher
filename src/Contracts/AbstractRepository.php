<?php

namespace App\Contracts;

use Guillermoandrae\Repositories\AbstractRepository as AbstractBaseRepository;

abstract class AbstractRepository extends AbstractBaseRepository
{
    public const DEFAULT_LIMIT = 25;
}
