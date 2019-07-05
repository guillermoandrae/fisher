<?php

namespace Guillermoandrae\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;

interface RequestInterface
{
    /**
     * Registers the JSON Marshaler with this object.
     *
     * @param Marshaler $marshaler The JSON Marshaler.
     * @return RequestInterface An implementation of this interface.
     */
    public function setMarshaler(Marshaler $marshaler): RequestInterface;

    /**
     * Sets the table name.
     *
     * @param string $tableName The table name.
     * @return RequestInterface An implementation of this interface.
     */
    public function setTableName(string $tableName): RequestInterface;

    /**
     * Returns the query.
     *
     * @return array The query.
     */
    public function get(): array;
}
