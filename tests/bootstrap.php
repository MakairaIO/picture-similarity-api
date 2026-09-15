<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Process\PhpSubprocess;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

$output = new Symfony\Component\Console\Output\ConsoleOutput();

$spawnConsole = static function (string ...$args) {
    $command = array_merge(['bin/console', '--ansi', '-n', '--env=test'], $args);
    $p = new PhpSubprocess($command);
    $p->mustRun();
    echo $p->getOutput();
};

$spawnConsole('doctrine:database:drop', '--force');
$spawnConsole('doctrine:database:create');
$spawnConsole('doctrine:schema:create');
$spawnConsole('doctrine:fixtures:load', '--append');
