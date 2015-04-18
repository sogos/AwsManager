<?php

namespace Sogos\Bundle\AwsBundle\AwsServices;

use Aws\Rds\RdsClient;

class AwsRdsClient
{
    protected $rdsClient;
    protected $region;
    protected $awsIamClient;

    /**
     * @param AwsIamClient $awsIamClient
     */
    public function __construct($region, AwsIamClient $awsIamClient)
    {
        $this->awsIamClient = $awsIamClient;
        $this->region = $region;
        $this->rdsClient = RdsClient::factory(array(
            'profile' => 'default',
            'region'  => 'eu-west-1',
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
            return $this->rdsClient->describeDBInstances(
                array('DBInstanceIdentifier' => $instanceName)
            );
        } else {
            return $this->rdsClient->describeDBInstances(
            );
        }
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

        return $tag_list->toArray();
    }
}
