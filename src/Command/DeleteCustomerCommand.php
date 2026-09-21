<?php

namespace Makaira\PictureSimilarity\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Makaira\PictureSimilarity\Doctrine\DBAL\DatabaseNameNormalizerTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function preg_replace;
use function sprintf;

#[AsCommand('customer:delete', description: 'Remove all databases of a customer.')]
class DeleteCustomerCommand extends Command
{
    use DatabaseNameNormalizerTrait;

    public function __construct(private readonly Connection $connection, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument(
            'customers',
            InputArgument::REQUIRED | InputArgument::IS_ARRAY,
            'Name of the customer. This is the subdomain part from *.makaira.io or the whole domain.',
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($input->getArgument('customers') as $customer) {
            $output->writeln(sprintf('<fg=green>Remove databases of customer <fg=yellow>%s</>', $customer));
            $customerPrefix = $this->normalize(preg_replace('/\.makaira\.io$/', '', $customer));

            $databases = $this->connection->executeQuery(
                'SELECT `SCHEMA_NAME` FROM `information_schema`.`SCHEMATA` WHERE `SCHEMA_NAME` LIKE ?',
                ["{$customerPrefix}%"],
                [ParameterType::STRING],
            );

            foreach ($databases->iterateColumn() as $dbName) {
                $output->writeln(sprintf('<fg=green>Drop database <fg=yellow>%s</>', $dbName));
                $this->connection->executeStatement("DROP DATABASE `{$dbName}`");
            }
        }

        return Command::SUCCESS;
    }
}
