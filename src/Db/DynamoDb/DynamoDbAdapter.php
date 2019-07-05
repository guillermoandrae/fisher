<?php

namespace Guillermoandrae\Fisher\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use Aws\DynamoDb\DynamoDbClient;
use Guillermoandrae\Common\Collection;
use Guillermoandrae\Fisher\Db\DbException;
use Aws\DynamoDb\Exception\DynamoDbException;
use Guillermoandrae\Fisher\Db\AdapterInterface;
use Guillermoandrae\Common\CollectionInterface;
use Guillermoandrae\Repositories\RepositoryFactory;

final class DynamoDbAdapter implements AdapterInterface
{
    /**
     * @var DynamoDbClient The DynamoDb client.
     */
    private $client;

    /**
     * @var Marshaler The JSON Marshaler.
     */
    private $marshaler;

    /**
     * @var string The table name.
     */
    private $tableName;

    /**
     * Registers the client and marshaler with this object. Sets up the Repository factory and passes the Marshaler
     * over to the request factory as well.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The JSON Marshaler.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler)
    {
        $this->setClient($client);
        $this->marshaler = $marshaler;
        RepositoryFactory::setNamespace('Guillermoandrae\Fisher\Repositories');
        RequestFactory::setMarshaler($marshaler);
    }

    /**
     * {@inheritDoc}
     */
    public function findAll(int $offset = 0, ?int $limit = null): CollectionInterface
    {
        try {
            $query = RequestFactory::factory('scan', $this->tableName)->get();
            $results = $this->client->scan($query);
            $rows = [];
            foreach ($results['Items'] as $item) {
                $rows[] = $this->marshaler->unmarshalItem($item);
            }
            $collection = Collection::make($rows);
            return $collection->limit($offset, $limit);
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findLatest(): array
    {
        try {
            $query = RequestFactory::factory('scan', $this->tableName)
                ->setLimit(0, 1)
                ->get();
            $results = $this->client->scan($query);
            return $this->marshaler->unmarshalItem($results['Item']);
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findById($id): array
    {
        try {
            $query = RequestFactory::factory('get-item', $this->tableName, $id)->get();
            $results = $this->client->getItem($query);
            return $this->marshaler->unmarshalItem($results['Item']);
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function insert(array $data): bool
    {
        try {
            $query = RequestFactory::factory('put-item', $this->tableName, $data)->get();
            $this->client->putItem($query);
            return true;
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete($id): bool
    {
        try {
            $query = RequestFactory::factory('delete-item', $this->tableName, $id)->get();
            $this->client->deleteItem($query);
            return true;
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function useTable(string $tableName): AdapterInterface
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setClient($client): AdapterInterface
    {
        $this->client = $client;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getClient(): DynamoDbClient
    {
        return $this->client;
    }
}
