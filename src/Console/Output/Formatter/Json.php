<?php

namespace Makaira\PictureSimilarity\Console\Output\Formatter;

use Makaira\PictureSimilarity\Console\Output\FormatterInterface;

use const JSON_THROW_ON_ERROR;

class Json implements FormatterInterface
{
    protected bool $pretty = false;

    public function format(array $rawData, string $context): string
    {
        $encodeOptions = JSON_THROW_ON_ERROR;
        if ($this->pretty) {
            $encodeOptions |= JSON_PRETTY_PRINT;
        }

        return json_encode($rawData, $encodeOptions);
    }

    public static function getFormat(): string
    {
        return 'json';
    }
}
