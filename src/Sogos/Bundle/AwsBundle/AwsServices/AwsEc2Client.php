<?php

namespace Sogos\Bundle\AwsBundle\AwsServices;

use Aws\Ec2\Ec2Client;
use Aws\Iam\IamClient;
use Doctrine\Common\Collections\ArrayCollection;
use Sogos\Bundle\AwsBundle\Documents\VpcSecurityGroup;

class AwsEc2Client
{
    protected $region;
    protected $iamClient;
    protected $ec2Client;

    public function __construct($region, AwsIamClient $awsIamClient)
    {
        $this->region = $region;
        $this->awsIamClient = $awsIamClient;
        $this->ec2Client = Ec2Client::factory(array(
            'profile' => 'default',
            'region'  =>  $region,
        ));
    }

    /**
     * @return Ec2Client
     */
    public function getEc2Client()
    {
        return $this->ec2Client;
    }

    /**
     * @param $region
     * @param null $VpcSecurityGroupIds
     */
    public function getVpcSecurityGroups($region, $VpcSecurityGroupIds = null)
    {
        if($VpcSecurityGroupIds &&  !is_array($VpcSecurityGroupIds)) {
            $VpcSecurityGroupIds = array($VpcSecurityGroupIds);
        }


        $this->ec2Client->setRegion($region);
        $response = $this->ec2Client->describeSecurityGroups(array(
            'GroupIds' => $VpcSecurityGroupIds,
        ));
        $security_groups_response = $response->get('SecurityGroups');
        $security_groups_collection = new ArrayCollection();
        foreach($security_groups_response as $security_group_response) {
            $security_group = new VpcSecurityGroup();
            $security_group
                ->setOwnerId($security_group_response['OwnerId'])
                ->setGroupName($security_group_response['GroupName'])
                ->setVpcSecurityGroupId($security_group_response['GroupId'])
                ->setDescription($security_group_response['Description'])
                ->setVpcId($security_group_response['VpcId'])
            ;
            $security_groups_collection->add($security_group);

        }

        return $security_groups_collection;
    }

}
