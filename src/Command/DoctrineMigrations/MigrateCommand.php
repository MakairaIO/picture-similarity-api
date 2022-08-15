<?php

namespace Makaira\PictureSimilarity\Command\DoctrineMigrations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command\DoctrineCommand;
use Makaira\PictureSimilarity\Doctrine\DBAL\MultiDbConnectionWrapper;
use RuntimeException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function get_class;
use function sprintf;

class MigrateCommand extends DoctrineCommand
{
    private const MYSQL_SYSTEM_TABLES = [
        'information_schema',
        'mysql',
        'performance_schema',
        'sys',
    ];

    // public function __construct(
    //     private readonly Connection $connection,
    //     ?DependencyFactory $dependencyFactory = null,
    //     ?string $name = null,
    // ) {
    //     if (!$this->connection instanceof MultiDbConnectionWrapper) {
    //         throw new RuntimeException(
    //             sprintf(
    //                 'This requires %s wrapper, but got %s!',
    //                 MultiDbConnectionWrapper::class,
    //                 get_class($this->connection)
    //             )
    //         );
    //     }
    //
    //     parent::__construct($dependencyFactory, $name);
    // }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Exception
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getDependencyFactory()->getConnection();
        return 0;
        $databasesResult = $this->connection->executeQuery('SHOW DATABASES');
        $command         = $this->getApplication()->find('doctrine:migrations:migrate');
        $result          = 0;

        foreach ($databasesResult->iterateColumn() as $database) {
            if (!in_array(strtolower($database), self::MYSQL_SYSTEM_TABLES, true)) {
                $this->connection->selectDatabase($database);
                $result += $command->run($input, $output);
            }
        }

        return $result;
    }
}
