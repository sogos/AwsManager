<?php

namespace Sogos\Bundle\DynamoDBBundle\Command;

use Doctrine\Common\Inflector\Inflector;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
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
                        $documentMethods = $reflectionClass->getMethods();
                        $documentMethodsNames = array();
                        foreach($documentMethods as $documentMethod)
                        {
                            $documentMethodsNames[] = $documentMethod->getName();
                        }
                        foreach ($reflectionClass->getProperties() as $documentProperty) {
                            $propertiesAnnotations = $annotationReader->getPropertyAnnotations(new \ReflectionProperty($class, $documentProperty->getName()));
                            if (!empty($propertiesAnnotations)) {
                                $type = null;
                                foreach ($propertiesAnnotations as $propertiesAnnotation) {

                                    $baseAnnotationClassReflection = new \ReflectionClass($propertiesAnnotation);
                                    $baseAnnotationClassProperties = $baseAnnotationClassReflection->getProperties();
                                    foreach ($baseAnnotationClassProperties as $property) {
                                        $property_name = $property->getName();
                                        if( $propertiesAnnotation->$property_name) {
                                            if($property_name == "type") {
                                                $type = $propertiesAnnotation->$property_name;
                                            }
                                            $output->writeln(sprintf(' >>> Found annotation %s on property: <info>%s</info> with name <info>%s</info> and value: <info>%s</info>', get_class($propertiesAnnotation), $documentProperty->getName(), $property_name, $propertiesAnnotation->$property_name));

                                        }

                                    }
                                }
                                if($type) {
                                    if($type != "bool" && $type != "collection" ) {
                                        $expected_set_method = Inflector::camelize('set_'.$documentProperty->getName());
                                        $expected_get_method = Inflector::camelize('get_'.$documentProperty->getName());
                                        if(!in_array($expected_set_method, $documentMethodsNames)) {
                                            $output->writeln(sprintf("<error>Expected method %s() not found</error>", $expected_set_method));
                                        }
                                        if(!in_array($expected_get_method, $documentMethodsNames)) {
                                            $output->writeln(sprintf("<error>Expected method %s() not found</error>", $expected_get_method));
                                        }
                                    } elseif($type == 'bool') {
                                        $expected_set_method = Inflector::camelize('set_' . $documentProperty->getName());
                                        $expected_get_method = Inflector::camelize('is_' . $documentProperty->getName());
                                        if (!in_array($expected_set_method, $documentMethodsNames)) {
                                            $output->writeln(sprintf("<error>Expected method %s() not found</error>", $expected_set_method));
                                        }
                                        if (!in_array($expected_get_method, $documentMethodsNames)) {
                                            $output->writeln(sprintf("<error>Expected method %s() not found</error>", $expected_get_method));
                                        }
                                    } elseif($type == 'collection') {
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
    }
}
