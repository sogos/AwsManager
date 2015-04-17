<?php

namespace Sogos\Bundle\AwsBundle\AwsServices;


use Aws\DynamoDb\DynamoDbClient;

class AwsDynamoDBClient {


    protected $dynamoDBClient;

    /**
     * @param AwsIamClient $awsIamClient
     */
    public function __construct(AwsIamClient $awsIamClient)
    {

        $this->awsIamClient = $awsIamClient;
        $this->dynamoDBClient = DynamoDbClient::factory(array(
            'profile' => 'default',
            'region'  => 'eu-west-1'
        ));
    }
}