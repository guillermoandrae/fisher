<?php

namespace Guillermoandrae\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use Guillermoandrae\Common\ArrayableInterface;

abstract class AbstractRequest implements ArrayableInterface, RequestInterface
{
    /**
     * @var Marshaler The JSON Marshaler.
     */
    protected $marshaler;

    /**
     * @var string The table name.
     */
    protected $tableName;

    /**
     * Registers the Marshaler with this object.
     *
     * @param Marshaler $marshaler The JSON Marshaler.
     */
    public function __construct(Marshaler $marshaler)
    {
        $this->setMarshaler($marshaler);
    }

    /**
     * {@inheritDoc}
     */
    final public function setMarshaler(Marshaler $marshaler): RequestInterface
    {
        $this->marshaler = $marshaler;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    final public function setTableName(string $tableName): RequestInterface
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    final public function toArray(): array
    {
        return $this->get();
    }
}
