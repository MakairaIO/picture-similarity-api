<?php

namespace Makaira\PictureSimilarity\Console\Output\Formatter;

class PrettyXml extends Xml
{
    public static function getFormat(): string
    {
        return 'pretty-xml';
    }

    public function format(array $rawData, string $context): string
    {
        $this->pretty = true;

        return parent::format($rawData, $context);
    }
}
