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

                $output->writeln($bundle_namespace);
                foreach ($finder as $file) {
                    $reflectionClass = new \ReflectionClass($bundle_namespace.'\Documents\\'.$file->getBasename('.php'));
                    $classAnnotations = $annotationReader->getClassAnnotations($reflectionClass);
                    if (!empty($classAnnotations)) {
                        print_r($classAnnotations);
                    }
                }
            } catch (\InvalidArgumentException $e) {
            }
        }
    }
}
