<?php

namespace Ger\Bundle\AwsBundle\Controller;

use Aws\Rds\Exception\DBInstanceNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


/**
 * Class RdsController
 * @package Ger\Bundle\AwsBundle\Controller
 * @RouteResource("rds")
 */
class RdsController extends Controller
{
    /**
     * @return \Guzzle\Service\Resource\Model
     * @Route("/rds/{region}")
     * @Template("GerAwsBundle:Default:rds_instances.html.twig")
     * @ApiDoc(
     *  resource=true,
     *  description="Get All RDS DB Instances in One Region",
     *  filters={
     *      {"name"="region", "dataType"="string"}
     *  }
     * )
     */
    public function getAllInstancesByRegionAction($region)
    {
        return array('region' => $region, 'data' => $this->container->get('ger_aws.rds_client')->getDBInstances($region));
    }

    /**
     * @param $name
     * @return \Guzzle\Service\Resource\Model
     * @Route("/rds/{region}/{name}")
     * @ApiDoc(
     *  resource=true,
     *  description="Get One RDS DB Instance in One Region by Name",
     *  filters={
     *      {"name"="region", "dataType"="string"}
     *  }
     * )
     */
    public function getOneInstanceByRegionAction($region, $name)
    {
        return $this->container->get('ger_aws.rds_client')->getDBInstances($region, $name);
    }

    /**
     * @param $name
     * @param $region
     * @return \Guzzle\Service\Resource\Model
     * @Route("/rds/{region}/{name}/tags")
     * @Template("GerAwsBundle:Default:rds_tags.html.twig")
     * @ApiDoc(
     *  resource=true,
     *  description="Get All Tags for one Instance in one region by Name",
     *  filters={
     *      {"name"="region", "dataType"="string"}
     *  }
     * )
     */
    public function getOneInstanceTagsByRegionAction($region, $name)
    {
        $this->container->get('sogos_dynamo_db.connector');

        try {
            return array('data' => $this->container->get('ger_aws.rds_client')->getResourceTagsforDBInstance($region, $name));
        } catch(DBInstanceNotFoundException $e ) {
            throw new DBInstanceNotFoundException($e->getMessage(), 500);
        }


    }
}
