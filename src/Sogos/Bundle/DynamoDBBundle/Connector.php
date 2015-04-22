<?php

namespace Sogos\Bundle\DynamoDBBundle;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\ResourceNotFoundException;
use Monolog\Logger;

class Connector
{
    protected $dynamoDBClient;
    protected $database_name;
    protected $read_capacity_units;
    protected $write_capacity_units;
    protected $region;

    public function __construct(Logger $logger, $database_name, $region, $read_capacity_units, $write_capacity_units)
    {
        $this->database_name = $database_name;
        $this->region = $region;
        $this->read_capacity_units = $read_capacity_units;
        $this->write_capacity_units = $write_capacity_units;
        $this->dynamoDBClient = DynamoDbClient::factory(array(
            'profile' => 'default',
            'region'  => $region,
        ));
    }

    /**
     * @return mixed
     */
    public function getConfiguredTableName()
    {
        return $this->database_name;
    }

    /**
     * @return DynamoDbClient
     */
    public function getDynamoDBClient()
    {
        return $this->dynamoDBClient;
    }

    public function createTable($tableName, $schema)
    {
        if (!$this->checkIfTableExists($tableName)) {
            $this->dynamoDBClient->createTable($schema);
            $this->dynamoDBClient->waitUntil('TableExists', array(
                'TableName' => $tableName,
            ));
        }
    }

    public function destroyTable($tableName)
    {
        if ($this->checkIfTableExists($tableName)) {
            $this->dynamoDBClient->deleteTable(
              array(
                  'TableName' => $tableName,
              )
            );
            $this->dynamoDBClient->waitUntil('TableNotExists', array(
                'TableName' => $tableName,
            ));
        }
    }

    /**
     * @return bool
     */
    public function checkIfTableExists($tableName)
    {
        try {
            $this->dynamoDBClient->describeTable(
                array('TableName' => $tableName)
            );
        } catch (ResourceNotFoundException $e) {
            return false;
        }

        return true;
    }
}
