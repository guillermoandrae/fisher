<?php

namespace GuillermoandraeTest\Fisher;

use Aws\DynamoDb\DynamoDbClient;

final class LocalDynamoDbClient
{
    private static $instance;
    
    public static function get(): DynamoDbClient
    {
        if (!self::$instance) {
            self::$instance = new DynamoDbClient([
                'region' => 'us-west-2',
                'version'  => 'latest',
                'endpoint' => 'http://localhost:8000',
                'credentials' => [
                    'key' => 'not-a-real-key',
                    'secret' => 'not-a-real-secret',
                ],
            ]);
        }
        return self::$instance;
    }
}
