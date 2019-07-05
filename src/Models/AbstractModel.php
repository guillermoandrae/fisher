<?php

namespace Guillermoandrae\Fisher\Models;

use Guillermoandrae\Models\AbstractModel as AbstractBaseModel;

abstract class AbstractModel extends AbstractBaseModel
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Registers the data with this object.
     *
     * @param array $data  The object data.
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    final public function toArray(): array
    {
        return $this->data;
    }
}
