<?php

namespace Makaira\PictureSimilarity\Console\Output\Formatter;

use Makaira\PictureSimilarity\Console\Output\FormatterInterface;
use Symfony\Component\Yaml\Dumper;

class Yaml implements FormatterInterface
{
    protected bool $pretty = false;

    public function __construct(private readonly Dumper $dumper)
    {
    }

    public static function getFormat(): string
    {
        return 'yaml';
    }

    public function format(array $rawData, string $context): string
    {
        $inline = $this->pretty ? 10 : 0;

        return $this->dumper->dump($rawData, $inline);
    }
}
