<?php

namespace Makaira\PictureSimilarity\Command\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand("database:create", description: "Create a new database.")]
class CreateCommand extends Command
{
    public function __construct(private readonly Connection $connection)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('database', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->connection->executeStatement(
                "CREATE DATABASE IF NOT EXISTS `{$input->getArgument('database')}`",
            );
        } catch (Exception $e) {
            $this->getApplication()->renderThrowable($e, $output);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
