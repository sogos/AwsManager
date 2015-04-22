<?php

/**
 * Created by PhpStorm.
 * User: tcordier
 * Date: 18/04/15
 * Time: 13:48.
 */
namespace Sogos\Bundle\AwsBundle\Documents;

class Tag
{
    protected $key_name;
    protected $value;


    public function getKeyName()
    {
        return $this->key_name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setKeyName($keyName)
    {
        $this->key_name = $keyName;

        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
