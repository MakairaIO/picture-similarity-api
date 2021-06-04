<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}
$output = new Symfony\Component\Console\Output\ConsoleOutput();

$output->writeln('<comment>Dropping testing database.</comment>');
exec('bin/console --env test --no-interaction doctrine:database:drop --force');
$output->writeln('<info>Dropped testing database successfully.</info>');

$output->writeln('<comment>Creating testing database.</comment>');
exec('bin/console --env test --no-interaction doctrine:database:create');
$output->writeln('<info>Created testing database successfully.</info>');

$output->writeln('<comment>Generating tables.</comment>');
exec('bin/console --env=test --no-interaction doctrine:schema:create');
$output->writeln('<info>Generated tables successfully.</info>');

$output->writeln('<comment>Loading fixtures.</comment>');
exec('bin/console --env=test --no-interaction --append doctrine:fixtures:load');
$output->writeln('<info>Loaded fixtures successfully.</info>');
