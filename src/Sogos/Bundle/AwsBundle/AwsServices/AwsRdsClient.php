<?php

namespace Sogos\Bundle\AwsBundle\AwsServices;

use Aws\Rds\RdsClient;
use Doctrine\Common\Collections\ArrayCollection;
use Sogos\Bundle\AwsBundle\Documents\RdsInstances;
use Sogos\Bundle\AwsBundle\Documents\Tag;
use Sogos\Bundle\AwsBundle\Documents\VpcSecurityGroup;

class AwsRdsClient
{
    protected $rdsClient;
    protected $region;
    protected $awsIamClient;
    protected $awsEc2Client;

    /**
     * @param AwsIamClient $awsIamClient
     */
    public function __construct($region, AwsIamClient $awsIamClient, AwsEc2Client $awsEc2Client)
    {
        $this->awsIamClient = $awsIamClient;
        $this->region = $region;
        $this->awsEc2Client = $awsEc2Client;
        $this->rdsClient = RdsClient::factory(array(
            'profile' => 'default',
            'region'  => $region,
        ));
    }

    /**
     * @return RdsClient
     */
    public function getRdsClient()
    {
        return $this->rdsClient;
    }

    /**
     * @param $region
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getDBInstances($region, $instanceName = null)
    {
        $this->rdsClient->setRegion($region);
        if ($instanceName) {
            $instances = $this->rdsClient->describeDBInstances(
                array('DBInstanceIdentifier' => $instanceName)
            );
        } else {
            $instances = $this->rdsClient->describeDBInstances(
            );
        }

        $instanceCollection = new ArrayCollection();
        foreach($instances->get('DBInstances') as $instance) {
            //var_dump($instance);
            $rdsDBInstance = new RdsInstances();
            $rdsDBInstance
                ->setRegion($region)
                ->setAccountId($this->awsIamClient->getAccountId())
                ->setName($instance['DBInstanceIdentifier'])
                ->setInstanceType($instance['DBInstanceClass'])
                ->setEndpoint($instance['Endpoint']['Address'])
                ->setPortEndpoint($instance['Endpoint']['Port'])
                ->setAllocatedStorage($instance['AllocatedStorage'])
                ->setStorageType($instance['StorageType'])
                ->setEngine($instance['Engine'])
                ->setEngineVersion($instance['EngineVersion'])
                ->setLicence($instance['LicenseModel'])
                ->setAvailabilityZone($instance['AvailabilityZone'])
                ->setStatus($instance['DBInstanceStatus'])
                ->setRetentionPeriod($instance['BackupRetentionPeriod'])
                ->setPreferredBackupWindow($instance['PreferredBackupWindow'])
                ->setPreferredMaintenanceWindow($instance['PreferredMaintenanceWindow'])
                ->setMasterUserName($instance['MasterUsername'])
                ->setInstanceCreateTime($instance['InstanceCreateTime'])

            ;

                if(array_key_exists('LatestRestorableTime', $instance)) {
                    $rdsDBInstance
                        ->setLatestRestorableTime($instance['LatestRestorableTime'])
                    ;
                }
                if(array_key_exists('Iops', $instance)) {
                    $rdsDBInstance
                        ->setIops($instance['Iops'])
                        ;
                }

                if(array_key_exists('ReadReplicaDBInstanceIdentifiers', $instance)) {
                    foreach($instance['ReadReplicaDBInstanceIdentifiers'] as $replica) {
                        $rdsDBInstance
                            ->addReadReplica('arn:aws:rds:'.$region.':'.$this->awsIamClient->getAccountId().':db:'. $replica);
                    }
                }

                if(array_key_exists('ReadReplicaSourceDBInstanceIdentifier', $instance)) {
                    $rdsDBInstance
                        ->setReadReplicaParent($instance['ReadReplicaSourceDBInstanceIdentifier']);
                }

                if(array_key_exists('VpcSecurityGroups', $instance) &&  !empty($instance['VpcSecurityGroups'])) {
                    $VpcSecurityGroupIds = array();
                    foreach ($instance['VpcSecurityGroups'] as $InstanceVpcSecurityGroup) {
                        $VpcSecurityGroupIds[] = $InstanceVpcSecurityGroup['VpcSecurityGroupId'];
                    }
                    $VpcSecurityGroupCollection  = $this->awsEc2Client->getVpcSecurityGroups($region, $VpcSecurityGroupIds);
                    foreach($VpcSecurityGroupCollection as $VpcSecurityGroup) {
                        $rdsDBInstance->addVpcSecurityGroup($VpcSecurityGroup);
                    }
                }

                $tags_response = $this->getResourceTagsforDBInstance($region, $rdsDBInstance->getName());
                foreach($tags_response as $tag_to_add) {
                    $tag = new Tag();
                    $tag->setKeyName($tag_to_add['Key']);
                    $tag->setValue($tag_to_add['Value']);
                    $rdsDBInstance
                        ->addTag($tag);
                }

            $instanceCollection->add($rdsDBInstance);
        }
        return $instanceCollection;
    }

    /**
     * @param $region
     * @param $instanceName
     *
     * @return array
     */
    public function getResourceTagsforDBInstance($region, $instanceName)
    {
        $account_id = $this->awsIamClient->getAccountId();
        $this->rdsClient->setRegion($region);

        $tag_list =  $this->rdsClient->listTagsForResource(array('ResourceName' => 'arn:aws:rds:'.$region.':'.$account_id.':db:'.$instanceName));

        return $tag_list->get('TagList');
    }


}
