<?php

namespace Sogos\Bundle\AwsBundle\Documents;

use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBDocument;
use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBKey;
use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBType;
use JMS\Serializer\Annotation\Accessor;

/**
 * Class RdsInstances.
 *
 * @DynamoDBDocument(resource="yes")
 */
class RdsInstances
{

    /**
     * @var string $arn
     * @DynamoDBKey(index_level="primary", type="hash")
     * @DynamoDBType(type="string")
     * @Accessor(getter="getArn")
     */
    protected $arn;
    /**
     * @var string $account_id
     * @DynamoDBKey(index_level="primary", type="hash")
     * @DynamoDBType(type="string")
     */
    protected $account_id;

    /**
     * @var string $name
     * @DynamoDBType(type="string")
     * @DynamoDBKey(index_level="secondary", type="hash")
     */
    protected $name;
    /**
     * @var string $storage_type
     * @DynamoDBType(type="string")
     */
    protected $storage_type;
    /**
     * @var bool $multi_az
     * @DynamoDBType(type="bool")
     */
    protected $multi_az;
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $tags
     * @DynamoDBType(type="collection", target="Sogos\Bundle\AwsBundle\Documents\Tag")
     */

    protected $tags;
    /**
     * @var string $availability_zone;
     * @DynamoDBType(type="string")
     *
     */
    protected $availability_zone;
    /**
     * @var string $status
     * @DynamoDBType(type="string")
     */
    protected $status;
    /**
     * @var integer $retention_period
     * @DynamoDBType(type="integer")
     */
    protected $retention_period;
    /**
     * @var string $region
     * @DynamoDBKey(index_level="primary", type="hash")
     * @DynamoDBType(type="string")
     */
    protected $region;
    /**
     * @var string $instance_type
     * @DynamoDBType(type="string")
     */
    protected $instance_type;
    /**
     * @var string $endpoint
     * @DynamoDBType(type="string")
     */
    protected $endpoint;
    /**
     * @var string $port_endpoint
     * @DynamoDBType(type="string")
     */
    protected $port_endpoint;
    /**
     * @var string $engine
     * @DynamoDBType(type="string")
     */
    protected $engine;
    /**
     * @var string $engine_version
     * @DynamoDBType(type="string")
     */
    protected $engine_version;
    /**
     * @var string $licence
     * @DynamoDBType(type="string")
     */
    protected $licence;
    /**
     * @var integer $allocated_storage
     * @DynamoDBType(type="integer")
     */
    protected $allocated_storage;
    /**
     * @var \Datetime $instance_create_time
     * @DynamoDBType(type="datetime")
     */
    protected $instance_create_time;
    /**
     * @var integer $iops
     * @DynamoDBType(type="integer")
     */
    protected $iops;
    /**
     * @var bool $read_replica
     * @DynamoDBType(type="bool")
     */
    protected $read_replica;
    /**
     * @var string $read_replica_parent
     * @DynamoDBType(type="string")
     */
    protected $read_replica_parent;
    /**
     * @var string $preferred_backup_window
     * @DynamoDBType(type="string")
     */
    protected $preferred_backup_window;
    /**
     * @var string $preferred_maintenance_window
     * @DynamoDBType(type="string")
     */
    protected $preferred_maintenance_window;
    /**
     * @var string $latest_restorable_time
     * @DynamoDBType(type="string")
     */
    protected $latest_restorable_time;
    /**
     * @var string $master_user_name;
     * @DynamoDBType(type="string")
     */
    protected $master_user_name;
    /**
     * @var \Array $read_replicas
     * @DynamoDBType(type="array")
     *
     */
    protected $read_replicas;

    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->read_replicas = array();
    }

    /**
     * @return string
     */
    public function getArn()
    {
        if(!$this->arn) {
            $this->arn = 'arn:aws:rds:'.$this->getRegion().':'.$this->getAccountId().':db:'.$this->getName();
        }
        return $this->arn;
    }

    /**
     * @return string
     */
    public function getAccountId()
    {
        return $this->account_id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStorageType()
    {
        return $this->storage_type;
    }

    /**
     * @return bool
     */
    public function isMultiAz()
    {
        return $this->multi_az;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getAvailabilityZone()
    {
        return $this->availability_zone;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getRetentionPeriod()
    {
        return $this->retention_period;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getInstanceType()
    {
        return $this->instance_type;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return string
     */
    public function getPortEndpoint()
    {
        return $this->port_endpoint;
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @return string
     */
    public function getEngineVersion()
    {
        return $this->engine_version;
    }

    /**
     * @return string
     */
    public function getLicence()
    {
        return $this->licence;
    }

    /**
     * @return int
     */
    public function getAllocatedStorage()
    {
        return $this->allocated_storage;
    }

    /**
     * @return \Datetime
     */
    public function getInstanceCreateTime()
    {
        return $this->instance_create_time;
    }

    /**
     * @return int
     */
    public function getIops()
    {
        return $this->iops;
    }

    /**
     * @return bool
     */
    public function isReadReplica()
    {
        return $this->is_read_replica;
    }

    /**
     * @return RdsInstances
     */
    public function getReadReplicaParent()
    {
        return $this->read_replica_parent;
    }

    /**
     * @return string
     */
    public function getPreferredBackupWindow()
    {
        return $this->preferred_backup_window;
    }

    /**
     * @return string
     */
    public function getPreferredMaintenanceWindow()
    {
       return $this->preferred_maintenance_window;
    }

    /**
     * @return string
     */
    public function getLatestRestorableTime()
    {
        return $this->latest_restorable_time;
    }

    /**
     * @return string
     */
    public function getMasterUserName()
    {
        return $this->master_user_name;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getReadReplicas()
    {
        return $this->read_replicas;
    }

    /**
     * @param $arn
     * @return $this
     */
    public function setArn($arn)
    {
        $this->arn = $arn;
        return $this;
    }

    /**
     * @param $accountId
     * @return $this
     */
    public function setAccountId($accountId)
    {
        $this->account_id = $accountId;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param $storageType
     * @return $this
     */
    public function setStorageType($storageType)
    {
        $this->storage_type = $storageType;
        return $this;
    }

    /**
     * @param $multiAZ
     * @return $this
     */
    public function setMultiAz($multiAZ)
    {
        $this->multi_az = $multiAZ;
        return $this;
    }

    /**
     * @param Tag $tag
     * @return $this
     */
    public function addTag(Tag $tag)
    {
        $this->tags->add($tag);
        return $this;
    }

    /**
     * @param Tag $tag
     * @return $this
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->remove($tag);
        return $this;
    }

    public function setAvailabilityZone($availabilityZone)
    {
        $this->availability_zone = $availabilityZone;
        return $this;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param $retentionPeriod
     * @return $this
     */
    public function setRetentionPeriod($retentionPeriod)
    {
        $this->retention_period = $retentionPeriod;
        return $this;
    }

    /**
     * @param $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @param $instanceType
     * @return $this
     */
    public function setInstanceType($instanceType)
    {
        $this->instance_type = $instanceType;
        return $this;
    }

    /**
     * @param $endpoint
     * @return $this
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @param $PortEndpoint
     * @return $this
     */
    public function setPortEndpoint($PortEndpoint)
    {
        $this->port_endpoint = $PortEndpoint;
        return $this;
    }

    /**
     * @param $engine
     * @return $this
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
        return $this;
    }

    /**
     * @param $engine_version
     * @return $this
     */
    public function setEngineVersion($engine_version)
    {
        $this->engine_version = $engine_version;
        return $this;
    }

    /**
     * @param $licence
     * @return $this
     */
    public function setLicence($licence)
    {
        $this->licence = $licence;
        return $this;
    }

    /**
     * @param $allocatedStorage
     * @return $this
     */
    public function setAllocatedStorage($allocatedStorage)
    {
        $this->allocated_storage = $allocatedStorage;
        return $this;
    }

    /**
     * @param $create_time
     * @return $this
     */
    public function setInstanceCreateTime($create_time)
    {
        $this->instance_create_time = $create_time;
        return $this;
    }

    /**
     * @param $iops
     * @return $this
     */
    public function setIops($iops)
    {
        $this->iops = $iops;
        return $this;
    }

    /**
     * @param $isreadReplica
     * @return $this
     */
    public function setReadReplica($isreadReplica)
    {
        $this->is_read_replica = $isreadReplica;
        return $this;
    }

    /**
     * @param $parentRDSDBInstanceArn
     * @return $this
     */
    public function setReadReplicaParent($parentRDSDBInstanceArn)
    {
        $this->read_replica_parent = $parentRDSDBInstanceArn;
        return $this;
    }


    public function setPreferredBackupWindow($preferred_backup_window)
    {
        $this->preferred_backup_window = $preferred_backup_window;
        return $this;
    }


    public function setPreferredMaintenanceWindow($preferred_maintenance_window)
    {
        $this->preferred_maintenance_window = $preferred_maintenance_window;
        return $this;
    }

    public function setLatestRestorableTime($latest_restorable_time)
    {
        $this->latest_restorable_time = $latest_restorable_time;
        return $this;
    }

    /**
     * @param $masterUserName
     * @return $this
     */
    public function setMasterUserName($masterUserName)
    {
        $this->master_user_name = $masterUserName;
        return $this;
    }

    /**
     * @param RdsInstances $rdsInstance
     * @return $this
     */
    public function addReadReplica($rdsInstanceArn)
    {
        $this->read_replicas[] = $rdsInstanceArn;
        return $this;
    }

    /**
     * @param RdsInstances $rdsInstance
     * @return $this
     */
    public function removeReadReplica(RdsInstances $rdsInstanceArn)
    {
        $key = array_search($rdsInstanceArn,$this->read_replicas);
        if($key!==false){
            unset($this->read_replicas[$key]);
        }
        return $this;
    }



}
