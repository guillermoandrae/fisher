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
     * Registers the JSON Marshaler and table name with this object.
     *
     * @param Marshaler $marshaler The JSON Marshaler.
     * @param string $tableName The table name.
     */
    public function __construct(Marshaler $marshaler, string $tableName)
    {
        $this->setMarshaler($marshaler);
        $this->setTableName($tableName);
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

    /**
     * {@inheritDoc}
     */
    public function get(): array
    {
        return [
            'TableName' => $this->tableName,
        ];
    }
}
