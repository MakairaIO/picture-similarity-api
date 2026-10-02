<?php

declare(strict_types=1);

namespace Makaira\PictureSimilarity\Command\Database;

use Doctrine\DBAL\Exception as DBALException;
use Makaira\PictureSimilarity\Console\Output\Formatter;
use Makaira\PictureSimilarity\Database\ListProvider;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'database:list', description: 'List all customer databases')]
class ListCommand extends Command
{
    public function __construct(private readonly ListProvider $listProvider, private readonly Formatter $formatter)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $formatDescription = sprintf(
            'Use different output format (supported formats: <info>%s</info>)',
            implode(', ', $this->formatter->getFormats())
        );
        $this
            ->addOption(
                name:        'format',
                mode:        InputOption::VALUE_REQUIRED,
                description: $formatDescription,
                default:     'text',
            );
    }


    public function __invoke(InputInterface $input, OutputInterface $output, Application $application): int
    {
        try {
            $databases = $this->listProvider->customerDatabases();
            $message   = $this->formatter->format($input->getOption('format'), $databases, 'database');

            $output->writeln($message);

            return Command::SUCCESS;
        } catch (DBALException $t) {
            $application->renderThrowable($t, $output);

            return Command::FAILURE;
        }
    }
}
