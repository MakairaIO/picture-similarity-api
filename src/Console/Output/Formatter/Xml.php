<?php

namespace Makaira\PictureSimilarity\Console\Output\Formatter;

use Makaira\PictureSimilarity\Console\Output\FormatterInterface;
use XMLWriter;

class Xml implements FormatterInterface
{
    protected bool $pretty = false;

    public static function getFormat(): string
    {
        return 'xml';
    }

    public function format(array $rawData, string $context): string
    {
        $xml = new XMLWriter();
        $xml->openMemory();

        if ($this->pretty) {
            $xml->setIndentString('  ');
            $xml->setIndent(true);
        }

        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement("{$context}s");

        foreach ($rawData as $database) {
            $xml->startElement($context);
            $xml->text($database);
            $xml->endElement();
        }

        $xml->endElement();

        return $xml->outputMemory();
    }
}
