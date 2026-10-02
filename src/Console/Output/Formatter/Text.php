<?php

namespace Makaira\PictureSimilarity\Console\Output\Formatter;

use Makaira\PictureSimilarity\Console\Output\FormatterInterface;

class Text implements FormatterInterface
{
    public static function getFormat(): string
    {
        return 'text';
    }

    public function format(array $rawData, string $context): string
    {
        return implode("\n", $rawData);
    }
}
