<?php

namespace Makaira\PictureSimilarity\Console\Output\Formatter;

class PrettyYaml extends Yaml
{
    public static function getFormat(): string
    {
        return 'pretty-yaml';
    }

    public function format(array $rawData, string $context): string
    {
        $this->pretty = true;

        return parent::format($rawData, $context);
    }

}
