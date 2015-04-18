<?php

namespace Sogos\Bundle\DynamoDBBundle\Command;

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
            ->setName('dynamodb:verify_mapping')
            ->setDescription('Verifiy Mapping');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        // Search in All Bundle Documents folder for Annotations
        $bundles = $container->getParameter('kernel.bundles');
        $annotationReader = new AnnotationReader();
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
                        foreach ($reflectionClass->getMethods() as $method) {
                            $methodsAnnotations = $annotationReader->getMethodAnnotations(new \ReflectionMethod($class, $method->getName()));
                            if (!empty($methodsAnnotations)) {
                                foreach ($methodsAnnotations as $methodAnnotation) {
                                    $baseAnnotationClassReflection = new \ReflectionClass($methodAnnotation);
                                    $baseAnnotationClassProperties = $baseAnnotationClassReflection->getProperties();
                                    foreach ($baseAnnotationClassProperties as $property) {
                                        $property_name = $property->getName();
                                        $output->writeln(sprintf(' >>> Found annotation %s on method: %s() with name <info>%s</info> and value: <info>%s</info>', get_class($methodAnnotation), $method->getName(), $property_name, $methodAnnotation->$property_name));
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
