<?php

namespace Sogos\Bundle\AwsBundle\Documents;

use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBDocument;
use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBKey;

/**
 * Class RdsInstances.
 *
 * @DynamoDBDocument(resource="yes")
 */
class RdsInstances
{
    protected $name;
    protected $storage_type;
    protected $multi_az;
    protected $tags;
    protected $region;

    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getStorageType()
    {
        return $this->storage_type;
    }

    /**
     * @return mixed
     * @DynamoDBKey(index_level="secondary", type="hash")
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     * @DynamoDBKey(index_level="primary", type="hash")
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return mixed
     */
    public function isMultiAZ()
    {
        return $this->multi_az;
    }

    /**
     * @param $storage_type
     *
     * @return $this
     */
    public function setStorageType($storage_type)
    {
        $this->storage_type = $storage_type;

        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $region
     *
     * @return $this
     */
    public function setRegion($region)
    {
        $this->name = $region;

        return $this;
    }

    /**
     * @param $multi_az
     *
     * @return $this
     */
    public function setMultiAZ($multi_az)
    {
        $this->multi_az = $multi_az;

        return $this;
    }

    /**
     * @param $tag
     *
     * @return $this
     */
    public function addTag(Tag $tag)
    {
        $this->tags->add($tag);

        return $this;
    }

    /**
     * @param $tag
     *
     * @return $this
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->remove($tag);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
