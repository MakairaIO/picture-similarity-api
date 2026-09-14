<?php

namespace Makaira\PictureSimilarity\EventListener;

use Makaira\PictureSimilarity\Kernel;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

use function basename;
use function explode;
use function get_class;

#[AsEventListener(KernelEvents::EXCEPTION, method: 'onKernelException', priority: -32)]
final readonly class ExceptionListener
{
    public function __construct(private Kernel $kernel)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $t = $event->getThrowable();
        $a = $this->formatException($t);

        if (null !== ($previous = $t->getPrevious())) {
            do {
                $a['previous'] = $this->formatException($previous);
            } while (null !== ($previous = $previous->getPrevious()));
        }

        $event->setResponse(new JsonResponse($a));
    }

    private function formatException(Throwable $t): array
    {
        $a = [
            'class'   => get_class($t),
            'message' => basename($t->getMessage()),
            'code'    => $t->getCode(),
        ];

        if ('prod' !== $this->kernel->getEnvironment()) {
            $a += [
                'message' => $t->getMessage(),
                'file'    => $t->getFile(),
                'line'    => $t->getLine(),
                'trace'   => explode("\n", $t->getTraceAsString()),
            ];
        }

        return $a;
    }
}
