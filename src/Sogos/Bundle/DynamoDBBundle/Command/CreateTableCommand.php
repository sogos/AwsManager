<?php

namespace Sogos\Bundle\DynamoDBBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class CreateTableCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('dynamodb:create_table')
            ->setDescription('Create DynamoDB Table');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $dynamodb_connector = $container->get("sogos_dynamo_db.connector");
        $table_name = $dynamodb_connector->getConfiguredTableName();
        if(!$dynamodb_connector->checkIfTableExists()) {
            $output->writeln("Creating table and waiting for confirmation...");
            $dynamodb_connector->createTable();
            $output->writeln("<info>Done !</info>");

        } else {
            $output->writeln(sprintf("<error>Table %s already exists</error>", $table_name));
        }


    }


}