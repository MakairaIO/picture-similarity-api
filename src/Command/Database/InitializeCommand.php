<?php

declare(strict_types=1);

namespace Makaira\PictureSimilarity\Command\Database;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(name: 'database:initialize', description: 'Initialize new database')]
class InitializeCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('database', InputArgument::REQUIRED);
    }

    public function __invoke(InputInterface $input, OutputInterface $output, Application $application): int
    {
        try {
            $database    = $input->getArgument('database');
            $createInput = new ArrayInput(
                [
                    'command'  => 'database:create',
                    'database' => $database,
                ]
            );

            $createInput->setInteractive(false);

            $ret = $application->doRun($createInput, $output);
            if ($ret !== Command::SUCCESS) {
                return $ret;
            }

            $migrateInput = new ArrayInput(
                [
                    'command' => 'doctrine:migrations:migrate',
                    '--database' => $database,
                ]
            );

            $migrateInput->setInteractive(false);

            $application->doRun($migrateInput, $output);
        } catch (Throwable $t) {
            $application->renderThrowable($t, $output);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
