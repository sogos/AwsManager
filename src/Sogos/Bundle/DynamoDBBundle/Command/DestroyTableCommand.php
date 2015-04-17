<?php

namespace Sogos\Bundle\DynamoDBBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class DestroyTableCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('dynamodb:destroy_table   ')
            ->setDescription('Destroy DynamoDB Table');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $dynamodb_connector = $container->get("sogos_dynamo_db.connector");
        $table_name = $dynamodb_connector->getConfiguredTableName();
        if($dynamodb_connector->checkIfTableExists()) {
            $output->writeln("Destroying table and waiting for confirmation...");
            $dynamodb_connector->destroyTable();
            $output->writeln("<info>Done !</info>");

        } else {
            $output->writeln(sprintf("<error>Table %s doesn't exists</error>", $table_name));
        }


    }


}