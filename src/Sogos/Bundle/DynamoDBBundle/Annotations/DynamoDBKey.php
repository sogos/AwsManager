<?php

namespace Sogos\Bundle\DynamoDBBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
class DynamoDBKey
{
    public $index_level;
    public $type;
    public $index_name;
    public $read_capacity_units;
    public $write_capacity_units;
    public $projection_type;
}
