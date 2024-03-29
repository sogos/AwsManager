<?php

namespace Sogos\Bundle\AwsBundle\Documents;

use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBDocument;
use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBKey;
use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBType;
use JMS\Serializer\Annotation\Accessor;

/**
 * Class RdsInstances.
 *
 * @DynamoDBDocument(resource="yes", name="rds")
 */
class RdsInstances
{

    /**
     * @var string $region
     * @DynamoDBKey(index_level="primary", type="HASH")
     * @DynamoDBType(type="S")
     */
    protected $region;
    /**
     * @var string $arn
     * @DynamoDBKey(index_level="primary", type="RANGE")
     * @DynamoDBType(type="S")
     * @Accessor(getter="getArn")
     */
    protected $arn;

    /**
     * @var string $account_id
     * @DynamoDBKey(index_level="global_secondary", type="HASH", index_name="account", projection_type="KEYS_ONLY", read_capacity_units=1, write_capacity_units=1)
     * @DynamoDBType(type="S")
     */
    protected $account_id;
    /**
     * @var string $name
     * @DynamoDBType(type="S")
     * @DynamoDBKey(index_level="global_secondary", type="HASH", index_name="name", projection_type="KEYS_ONLY", read_capacity_units=1, write_capacity_units=1)
     */
    protected $name;
    /**
     * @var string $storage_type
     * @DynamoDBType(type="S")
     */
    protected $storage_type;
    /**
     * @var bool $multi_az
     * @DynamoDBType(type="S")
     */
    protected $multi_az;
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $tags
     * @DynamoDBType(type="M", target="Sogos\Bundle\AwsBundle\Documents\Tag")
     */

    protected $tags;
    /**
     * @var string $availability_zone;
     * @DynamoDBType(type="S")
     *
     */
    protected $availability_zone;
    /**
     * @var string $status
     * @DynamoDBType(type="S")
     */
    protected $status;
    /**
     * @var integer $retention_period
     * @DynamoDBType(type="N")
     */
    protected $retention_period;
    /**
     * @var string $instance_type
     * @DynamoDBType(type="S")
     */
    protected $instance_type;
    /**
     * @var string $endpoint
     * @DynamoDBType(type="S")
     */
    protected $endpoint;
    /**
     * @var string $port_endpoint
     * @DynamoDBType(type="S")
     */
    protected $port_endpoint;
    /**
     * @var string $engine
     * @DynamoDBType(type="S")
     */
    protected $engine;
    /**
     * @var string $engine_version
     * @DynamoDBType(type="S")
     */
    protected $engine_version;
    /**
     * @var string $licence
     * @DynamoDBType(type="S")
     */
    protected $licence;
    /**
     * @var integer $allocated_storage
     * @DynamoDBType(type="N")
     */
    protected $allocated_storage;
    /**
     * @var \Datetime $instance_create_time
     * @DynamoDBType(type="N")
     */
    protected $instance_create_time;
    /**
     * @var integer $iops
     * @DynamoDBType(type="N")
     */
    protected $iops;
    /**
     * @var bool $read_replica
     * @DynamoDBType(type="BOOL")
     */
    protected $read_replica;
    /**
     * @var string $read_replica_parent
     * @DynamoDBType(type="S")
     */
    protected $read_replica_parent;
    /**
     * @var string $preferred_backup_window
     * @DynamoDBType(type="S")
     */
    protected $preferred_backup_window;
    /**
     * @var string $preferred_maintenance_window
     * @DynamoDBType(type="S")
     */
    protected $preferred_maintenance_window;
    /**
     * @var string $latest_restorable_time
     * @DynamoDBType(type="S")
     */
    protected $latest_restorable_time;
    /**
     * @var string $master_user_name;
     * @DynamoDBType(type="S")
     */
    protected $master_user_name;
    /**
     * @var \Array $read_replicas
     * @DynamoDBType(type="SS")
     *
     */
    protected $read_replicas;
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $vpc_security_groups
     */
    protected $vpc_security_groups;

    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vpc_security_groups = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getVpcSecurityGroups()
    {
        return $this->vpc_security_groups;
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

    /**
     * @param VpcSecurityGroup $vpcSecurityGroup
     * @return $this
     */
    public function addVpcSecurityGroup(VpcSecurityGroup $vpcSecurityGroup)
    {
        $this->vpc_security_groups->add($vpcSecurityGroup);
        return $this;
    }

    /**
     * @param Tag $tag
     * @return $this
     */
    public function removeVpcSecurityGroup(VpcSecurityGroup $vpcSecurityGroup)
    {
        $this->vpc_security_groups->remove($vpcSecurityGroup);
        return $this;
    }




}
