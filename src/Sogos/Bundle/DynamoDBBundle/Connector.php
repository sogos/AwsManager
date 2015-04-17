<?php

namespace Sogos\Bundle\DynamoDBBundle;


use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\ResourceNotFoundException;
use Monolog\Logger;

class Connector {


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
            'region'  => $region
        ));
    }

    /**
     * @return mixed
     */
    public function getConfiguredTableName()
    {
        return $this->database_name;
    }

    public function createTable() {
        if(!$this->checkIfTableExists()) {
            $this->dynamoDBClient->createTable(array(
                'TableName' => $this->database_name,
                'AttributeDefinitions' => array(
                    array(
                        'AttributeName' => 'id',
                        'AttributeType' => 'N'
                    ),
                    array(
                        'AttributeName' => 'time',
                        'AttributeType' => 'N'
                    )
                ),
                'KeySchema' => array(
                    array(
                        'AttributeName' => 'id',
                        'KeyType'       => 'HASH'
                    ),
                    array(
                        'AttributeName' => 'time',
                        'KeyType'       => 'RANGE'
                    )
                ),
                'ProvisionedThroughput' => array(
                    'ReadCapacityUnits'  => $this->read_capacity_units,
                    'WriteCapacityUnits' => $this->write_capacity_units
                )
            ));
            $this->dynamoDBClient->waitUntil('TableExists', array(
                'TableName' => $this->database_name
            ));
        }
    }

    public function destroyTable() {
        if($this->checkIfTableExists()) {
            $this->dynamoDBClient->deleteTable(
              array(
                  'TableName' => $this->database_name
              )
            );
            $this->dynamoDBClient->waitUntil('TableNotExists', array(
                'TableName' => $this->database_name
            ));

        }
    }


    /**
     * @return bool
     */
    public function checkIfTableExists() {
        try {
            $this->dynamoDBClient->describeTable(
                array('TableName' => $this->database_name)
            );
        } catch(ResourceNotFoundException $e) {
            return false;
        }
        return true;

    }

}