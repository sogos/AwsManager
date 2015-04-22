<?php

namespace Sogos\Bundle\DynamoDBBundle\Command;

use Doctrine\Common\Inflector\Inflector;
use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBDocument;
use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBKey;
use Sogos\Bundle\DynamoDBBundle\Annotations\DynamoDBType;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Doctrine\Common\Annotations\AnnotationReader;

class VerifyMappingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dynamodb:verify_schema')
            ->setDescription('Verifiy Mapping');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $found_dynamodb_tables = array();
        $container = $this->getContainer();
        // Search in All Bundle Documents folder for Annotations
        $bundles = $container->getParameter('kernel.bundles');
        $annotationReader = new AnnotationReader();
        // TODO Remettre tout ceci dans un service
        foreach ($bundles as $bundle_key => $fqdn_bundle) {
            try {
                $path = $container->get('kernel')->locateResource('@'.$bundle_key.'/Documents');
                $finder = new Finder();
                $finder->files()->name('*.php')->in($path);
                // Determine Bundle Root Namespace
                $bundle_class =  new \ReflectionClass($fqdn_bundle);
                $bundle_namespace = $bundle_class->getNamespaceName();
                foreach ($finder as $file) {
                    $class = $bundle_namespace.'\Documents\\'.$file->getBasename('.php');
                    $reflectionClass = new \ReflectionClass($class);
                    $classAnnotations = $annotationReader->getClassAnnotations($reflectionClass);
                    if (!empty($classAnnotations)) {
                        $output->writeln(sprintf('<info>[Found]</info> Found DynamoDB Document: <info>%s</info> in bundle: %s ', $file->getBasename('.php'), $bundle_key));
                        foreach($classAnnotations as $classAnnotation) {
                            if ($classAnnotation instanceof DynamoDBDocument) {
                                $found_dynamodb_tables[$classAnnotation->name] = array(
                                    'TableName' => $classAnnotation->name,
                                    'AttributeDefinitions' => array(),
                                    'KeySchema' => array(),
                                    'ProvisionedThroughput' => array(
                                        'ReadCapacityUnits'  => 1,
                                        'WriteCapacityUnits' => 1,
                                    ),
                                );
;                            }
                        }
                        $documentMethods = $reflectionClass->getMethods();
                        $documentMethodsNames = array();
                        foreach($documentMethods as $documentMethod)
                        {
                            $documentMethodsNames[] = $documentMethod->getName();
                        }
                        foreach ($reflectionClass->getProperties() as $documentProperty) {
                            $type_key = null;
                            $type = null;
                            $index_level = null;
                            $index_name = null;
                            $projection_type  = null;
                            $read_capacity_units = null;
                            $write_capacity_units = null;
                            $propertiesAnnotations = $annotationReader->getPropertyAnnotations(new \ReflectionProperty($class, $documentProperty->getName()));
                            if (!empty($propertiesAnnotations)) {
                                $type = null;
                                foreach ($propertiesAnnotations as $propertiesAnnotation) {
                                    $baseAnnotationClassReflection = new \ReflectionClass($propertiesAnnotation);
                                    $baseAnnotationClassProperties = $baseAnnotationClassReflection->getProperties();
                                    foreach ($baseAnnotationClassProperties as $property) {
                                        $property_name = $property->getName();
                                        if( $propertiesAnnotation->$property_name) {
                                            if($property_name == "type" && $propertiesAnnotation instanceof DynamoDBType) {
                                                $type = $propertiesAnnotation->$property_name;
                                            }
                                            if($property_name == "type" && $propertiesAnnotation instanceof DynamoDBKey) {
                                                $type_key = $propertiesAnnotation->$property_name;
                                            }
                                            if($property_name == "index_level" && $propertiesAnnotation instanceof DynamoDBKey) {
                                                $index_level = $propertiesAnnotation->$property_name;
                                            }
                                            if($property_name == "index_name" && $propertiesAnnotation instanceof DynamoDBKey) {
                                                $index_name = $propertiesAnnotation->$property_name;
                                            }
                                            if($property_name == "projection_type" && $propertiesAnnotation instanceof DynamoDBKey) {
                                                $projection_type = $propertiesAnnotation->$property_name;
                                            }
                                            if($property_name == "read_capacity_units" && $propertiesAnnotation instanceof DynamoDBKey) {
                                                $read_capacity_units = $propertiesAnnotation->$property_name;
                                            }
                                            if($property_name == "write_capacity_units" && $propertiesAnnotation instanceof DynamoDBKey) {
                                                $write_capacity_units = $propertiesAnnotation->$property_name;
                                            }
                                            $output->writeln(sprintf(' >>> Found annotation %s on property: <info>%s</info> with name <info>%s</info> and value: <info>%s</info>', get_class($propertiesAnnotation), $documentProperty->getName(), $property_name, $propertiesAnnotation->$property_name));

                                        }

                                    }
                                }
                                if($type_key && $index_level) {
                                    if($index_level == 'primary') {
                                        $found_dynamodb_tables[$classAnnotation->name]['KeySchema'][] =
                                            array(
                                                'AttributeName' => $documentProperty->getName(),
                                                'KeyType' => $type_key
                                            );
                                    } elseif($index_level == "local_secondary") {
                                        if( $index_name && $projection_type && $read_capacity_units && $write_capacity_units) {

                                        if(!array_key_exists('LocalSecondaryIndexes',$found_dynamodb_tables[$classAnnotation->name] )) {
                                            $found_dynamodb_tables[$classAnnotation->name]['LocalSecondaryIndexes'] = array();
                                        }
                                        $found_dynamodb_tables[$classAnnotation->name]['LocalSecondaryIndexes'][] =
                                            array(
                                                'IndexName' => $index_name,
                                                'KeySchema' => array(
                                                    array('AttributeName' => $documentProperty->getName(),    'KeyType' => $type_key)
                                                ),
                                                'Projection' => array(
                                                    'ProjectionType' => $projection_type,
                                                ),
                                                'ProvisionedThroughput' => array(
                                                    'ReadCapacityUnits'  => $read_capacity_units,
                                                    'WriteCapacityUnits' => $write_capacity_units
                                                )
                                            );
                                        } else {
                                            throw new \Exception("Missing parameters for GlobalSecondaryIndex");
                                        }
                                    } elseif($index_level == "global_secondary") {
                                        if( $index_name && $projection_type && $read_capacity_units && $write_capacity_units) {

                                            if(!array_key_exists('GlobalSecondaryIndexes',$found_dynamodb_tables[$classAnnotation->name] )) {
                                                $found_dynamodb_tables[$classAnnotation->name]['GlobalSecondaryIndexes'] = array();
                                            }
                                            $found_dynamodb_tables[$classAnnotation->name]['GlobalSecondaryIndexes'][] =
                                                array(
                                                    'IndexName' => $index_name,
                                                    'KeySchema' => array(
                                                        array('AttributeName' => $documentProperty->getName(),    'KeyType' => $type_key)
                                                    ),
                                                    'Projection' => array(
                                                        'ProjectionType' => $projection_type,
                                                    ),
                                                    'ProvisionedThroughput' => array(
                                                        'ReadCapacityUnits'  => $read_capacity_units,
                                                        'WriteCapacityUnits' => $write_capacity_units
                                                    )
                                                );
                                        } else {
                                            throw new \Exception("Missing parameters for GlobalSecondaryIndex");
                                        }
                                    }

                                }
                                if($type & $type_key && $index_level && ($index_level == "primary" || $index_level == "global_secondary" || $index_level = "local_secondary")) {
                                    switch($type) {
                                        case 'BOOL':
                                        case 'S':
                                        case 'N':
                                        case 'SS':
                                        case 'L':
                                        case 'BS':
                                            break;
                                        default:

                                            $output->writeln(sprintf("<error>Unexpected type %s() found for propery %s</error>", $type, $documentProperty->getName()));
                                            break;
                                        }
                                    $found_dynamodb_tables[$classAnnotation->name]['AttributeDefinitions'][] =
                                        array(
                                            'AttributeName' => $documentProperty->getName(),
                                            'AttributeType' => $type
                                        );

                                    if($type != "BOOL" && $type != "BS" && $type != "SS" ) {
                                        $expected_set_method = Inflector::camelize('set_'.$documentProperty->getName());
                                        $expected_get_method = Inflector::camelize('get_'.$documentProperty->getName());
                                        if(!in_array($expected_set_method, $documentMethodsNames)) {
                                            $output->writeln(sprintf("<error>Expected method %s() not found</error>", $expected_set_method));
                                        }
                                        if(!in_array($expected_get_method, $documentMethodsNames)) {
                                            $output->writeln(sprintf("<error>Expected method %s() not found</error>", $expected_get_method));
                                        }
                                    } elseif($type == 'BOOL') {
                                        $expected_set_method = Inflector::camelize('set_' . $documentProperty->getName());
                                        $expected_get_method = Inflector::camelize('is_' . $documentProperty->getName());
                                        if (!in_array($expected_set_method, $documentMethodsNames)) {
                                            $output->writeln(sprintf("<error>Expected method %s() not found</error>", $expected_set_method));
                                        }
                                        if (!in_array($expected_get_method, $documentMethodsNames)) {
                                            $output->writeln(sprintf("<error>Expected method %s() not found</error>", $expected_get_method));
                                        }
                                    } elseif($type == 'BS' || $type == 'SS') {
                                        $expected_add_method = Inflector::camelize('add_' . rtrim($documentProperty->getName(),'s'));
                                        $expected_remove_method = Inflector::camelize('remove_' . rtrim($documentProperty->getName(),'s'));
                                        $expected_get_method = Inflector::camelize('get_' . $documentProperty->getName());
                                        if(!in_array($expected_add_method, $documentMethodsNames)) {
                                            $output->writeln(sprintf("<error>Expected method %s() not found</error>", $expected_add_method));
                                        }
                                        if(!in_array($expected_remove_method, $documentMethodsNames)) {
                                            $output->writeln(sprintf("<error>Expected method %s() not found</error>", $expected_remove_method));
                                        }
                                        if(!in_array($expected_get_method, $documentMethodsNames)) {
                                            $output->writeln(sprintf("<error>Expected method %s() not found</error>", $expected_get_method));
                                        }
                                    }

                                }

                            }
                        }
                    }
                }
            } catch (\InvalidArgumentException $e) {
            }
        }
        print_r($found_dynamodb_tables);
        foreach($found_dynamodb_tables as $found_table) {
            if (!$container->get('sogos_dynamo_db.connector')->checkIfTableExists($found_table['TableName'])) {
                $container->get('sogos_dynamo_db.connector')->createTable($found_table['TableName'], $found_table);
            }
        }
    }
}
