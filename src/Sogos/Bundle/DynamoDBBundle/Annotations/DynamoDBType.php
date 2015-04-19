<?php

namespace Sogos\Bundle\DynamoDBBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
class DynamoDBType
{
    public $type;
    public $of;
}
