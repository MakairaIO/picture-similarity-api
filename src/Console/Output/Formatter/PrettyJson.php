<?php

namespace Makaira\PictureSimilarity\Console\Output\Formatter;

class PrettyJson extends Json
{
    public static function getFormat(): string
    {
        return 'pretty-json';
    }

    public function format(array $rawData, string $context): string
    {
        $this->pretty = true;

        return parent::format($rawData, $context);
    }
}
