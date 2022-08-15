<?php

namespace Makaira\PictureSimilarity\EventListener;

use Doctrine\DBAL\Connection;
use Doctrine\Migrations\Tools\Console\Command\DoctrineCommand;
use Makaira\PictureSimilarity\Doctrine\DBAL\DatabaseNameNormalizerTrait;
use Makaira\PictureSimilarity\Doctrine\DBAL\MultiDbConnectionWrapper;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: ConsoleEvents::COMMAND, method: 'onConsoleCommand')]
class DoctrineCommandListener
{
    use DatabaseNameNormalizerTrait;

    public function __construct(readonly private Connection $connection)
    {
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $command = $event->getCommand();
        if (!$command instanceof DoctrineCommand) {
            return;
        }

        $input = $event->getInput();
        if (!$input->hasParameterOption('--database')) {
            return;
        }

        $db = $input->getParameterOption('--database');

        if (!$this->connection instanceof MultiDbConnectionWrapper) {
            return;
        }

        $this->connection->selectDatabase($this->normalize($db));
        $command->addOption('database', null, InputOption::VALUE_REQUIRED);
    }
}
