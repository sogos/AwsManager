<?php

namespace Sogos\Bundle\AwsBundle\Documents;

use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBDocument;
use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBKey;
use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBType;

/**
 * Class RdsInstances.
 *
 * @DynamoDBDocument(resource="yes")
 */
class RdsInstances
{
    /**
     * @var $name
     * @DynamoDBType(type="string")
     * @DynamoDBKey(index_level="secondary", type="hash")
     */
    protected $name;
    /**
     * @var $storage_type
     * @DynamoDBType(type="string")
     */
    protected $storage_type;
    /**
     * @var $multi_az
     * @DynamoDBType(type="bool")
     */
    protected $multi_az;
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @DynamoDBType(type="collection", of="Sogos\Bundle\AwsBundle\Documents\Tag")
     */
    protected $tags;
    /**
     * @var $region
     * @DynamoDBType(type="string")
     */
    protected $region;
    /**
     * @var $instance_type
     * @DynamoDBKey(index_level="primary", type="hash")
     * @DynamoDBType(type="string")
     */
    protected $instance_type;
    /**
     * @var $endpoint
     * @DynamoDBType(type="string")
     */
    protected $endpoint;
    /**
     * @var $port_endpoint
     * @DynamoDBType(type="string")
     */
    protected $port_endpoint;
    /**
     * @var $engine
     * @DynamoDBType(type="string")
     */
    protected $engine;
    /**
     * @var $engine_version
     * @DynamoDBType(type="string")
     */
    protected $engine_version;
    /**
     * @var $licence
     * @DynamoDBType(type="string")
     */
    protected $licence;
    /**
     * @var $allocated_storage
     * @DynamoDBType(type="integer")
     */
    protected $allocated_storage;
    /**
     * @var $instance_create_time
     * @DynamoDBType(type="datetime")
     */
    protected $instance_create_time;
    /**
     * @var $iops
     * @DynamoDBType(type="integer")
     */
    protected $iops;

    /**
     * @var $read_replica
     * @DynamoDBType(type="bool")
     */
    protected $read_replica;

    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
