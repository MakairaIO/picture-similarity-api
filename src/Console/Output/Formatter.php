<?php

namespace Makaira\PictureSimilarity\Console\Output;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Traversable;

use function iterator_to_array;

readonly class Formatter
{
    private array $formatter;

    /**
     * @param iterable<FormatterInterface> $outputFormatter
     */
    public function __construct(
        #[AutowireIterator(FormatterInterface::class, defaultIndexMethod: 'getFormat')]
        iterable $outputFormatter,
    ) {
        $this->formatter = $outputFormatter instanceof Traversable ? iterator_to_array(
            $outputFormatter,
        ) : $outputFormatter;
    }

    public function format(string $format, array $rawData, string $context): string
    {
        if (!isset($this->formatter[$format])) {
            throw new InvalidFormatException($format, $this->getFormats());
        }

        return $this->formatter[$format]->format($rawData, $context);
    }

    public function getFormats(): array
    {
        return array_keys($this->formatter);
    }
}
