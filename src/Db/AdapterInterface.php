<?php

namespace Guillermoandrae\Fisher\Db;

use Guillermoandrae\Common\CollectionInterface;

interface AdapterInterface
{
    /**
     * Retrieves all of the rows in a table. When an offset and limit are provided, the desired slice is returned.
     *
     * @param integer $offset OPTIONAL The offset.
     * @param integer $limit OPTIONAL The limit.
     * @return CollectionInterface A collection of rows.
     * @throws DbException Thrown when a query error occurs.
     */
    public function findAll(int $offset = 0, ?int $limit = null): CollectionInterface;

    /**
     * Retrieves the newest record in a table.
     *
     * @return array The latest record.
     * @throws DbException Thrown when a query error occurs.
     */
    public function findLatest(): array;

    /**
     * Retrieves a record from a table by ID.
     *
     * @param mixed $id Thew record ID.
     * @return array The record.
     */
    public function findById($id): array;

    /**
     * Inserts a record into a database table.
     *
     * @param array $data The record data.
     * @return bool Whether or not the record creation was successful.
     * @throws DbException Thrown when a query error occurs.
     */
    public function insert(array $data): bool;

    /**
     * Deletes a record from a database table.
     *
     * @param mixed $id The record ID.
     * @return bool Whether or not the record deletion was successful.
     * @throws DbException Thrown when a query error occurs.
     */
    public function delete($id): bool;

    /**
     * Specifies the table to be used during the query.
     *
     * @param string $tableName The table name.
     * @return AdapterInterface An implementation of this interface.
     */
    public function useTable(string $tableName): AdapterInterface;

    /**
     * Registers the client.
     *
     * @param mixed The client.
     * @return AdapterInterface An implementation of this interface.
     */
    public function setClient($client): AdapterInterface;
    
    /**
     * Returns the client.
     *
     * @return mixed The client.
     */
    public function getClient();
}
