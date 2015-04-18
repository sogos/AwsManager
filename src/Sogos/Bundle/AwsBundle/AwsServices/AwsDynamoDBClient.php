<?php

namespace Sogos\Bundle\AwsBundle\AwsServices;

use Aws\DynamoDb\DynamoDbClient;

class AwsDynamoDBClient
{
    protected $region;
    protected $dynamoDBClient;

    /**
     * @param AwsIamClient $awsIamClient
     */
    public function __construct($region, AwsIamClient $awsIamClient)
    {
        $this->region = $region;

        $this->awsIamClient = $awsIamClient;
        $this->dynamoDBClient = DynamoDbClient::factory(array(
            'profile' => 'default',
            'region'  => $region,
        ));
    }
}
