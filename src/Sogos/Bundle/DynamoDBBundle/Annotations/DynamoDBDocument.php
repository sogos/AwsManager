<?php

namespace Sogos\Bundle\DynamoDBBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
class DynamoDBDocument
{
    public $resource;
    public $name;
}
