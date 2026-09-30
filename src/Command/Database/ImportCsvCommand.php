<?php

declare(strict_types=1);

namespace Makaira\PictureSimilarity\Command\Database;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(name: 'database:import-csv', description: 'Import a picture-similarity CSV file')]
class ImportCsvCommand extends Command
{
    public function __construct(private readonly Connection $connection)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('csv-file', InputArgument::REQUIRED);
        $this->addArgument('database', InputArgument::REQUIRED);
    }

    public function __invoke(
        InputInterface $input,
        OutputInterface $output,
        Application $application,
    ): int {
        try {
            $database  = $input->getArgument('database');
            $initInput = new ArrayInput(
                [
                    'command'  => 'database:initialize',
                    'database' => $database,
                ],
            );

            $ret = $application->doRun($initInput, $output);
            if ($ret !== Command::SUCCESS) {
                return Command::FAILURE;
            }

            $this->connection->executeStatement(
                "DELETE FROM `{$database}`.picture_similarity WHERE updated_at < DATE_SUB(NOW(), INTERVAL 1 WEEK)",
            );

            $importStatement = <<<EOT
LOAD DATA INFILE '{$input->getArgument('csv-file')}'
    INTO TABLE `{$database}`.picture_similarity 
    FIELDS TERMINATED BY ',' 
    OPTIONALLY ENCLOSED BY '"'
    ESCAPED BY '"'
    LINES TERMINATED BY '\\n'
    IGNORE 1 ROWS
    (product_id, similar_ids, type, shop, updated_at)
EOT;

            $this->connection->executeStatement($importStatement);
        } catch (Throwable $t) {
            $application->renderThrowable($t, $output);
        }

        return Command::SUCCESS;
    }
}
