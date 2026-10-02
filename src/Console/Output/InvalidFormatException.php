<?php

namespace Makaira\PictureSimilarity\Console\Output;

use JetBrains\PhpStorm\Pure;
use Makaira\PictureSimilarity\InvalidArgumentException;
use Throwable;

class InvalidFormatException extends InvalidArgumentException
{
    #[Pure]
    public function __construct(string $message, array $supportedFormats, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf(
            "Output format '%s' is not supported, supported formats are %s",
            $message,
            implode(', ', $supportedFormats),
        );

        parent::__construct($message, $code, $previous);
    }
}
