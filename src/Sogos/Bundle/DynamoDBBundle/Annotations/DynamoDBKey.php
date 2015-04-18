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
}
