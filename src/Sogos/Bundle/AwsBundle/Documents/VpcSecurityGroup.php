<?php

namespace Sogos\Bundle\AwsBundle\Documents;

class VpcSecurityGroup
{
    /**
     * @var string $VpcSecurityGroupId
     */
    protected $vpcSecurityGroupId;
    /**
     * @var string $OwnerId
     */
    protected $ownerId;
    /**
     * @var string $GroupName
     */
    protected $groupName;
    /**
     * @var string $Description
     */
    protected $description;
    /**
     * @var string $VpcId
     */
    protected $vpcId;

    /**
     * @return string
     */
    public function getVpcSecurityGroupId()
    {
        return $this->vpcSecurityGroupId;
    }

    /**
     * @return string
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getVpcId()
    {
        return $this->vpcId;
    }

    /**
     * @param $vpcSecurityGroupId
     * @return $this
     */
    public function setVpcSecurityGroupId($vpcSecurityGroupId)
    {
        $this->vpcSecurityGroupId = $vpcSecurityGroupId;
        return $this;
    }

    /**
     * @param $ownerId
     * @return $this
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    /**
     * @param $groupName
     * @return $this
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param $vpcId
     * @return $this
     */
    public function setVpcId($vpcId)
    {
        $this->vpcId = $vpcId;
        return $this;
    }
}
