<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/config', __DIR__ . '/migrations', __DIR__ . '/public'])
    ->withComposerBased(doctrine: true, phpunit: true, symfony: true)
    ->withAttributesSets(
        symfony: true,
        doctrine: true,
        mongoDb: true,
        gedmo: true,
        phpunit: true,
        fosRest: true,
        jms: true,
        sensiolabs: true,
        behat: true,
    );
