<?php

namespace Makaira\PictureSimilarity\Console\Output;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface FormatterInterface
{
    public static function getFormat(): string;
    public function format(array $rawData, string $context): string;
}
