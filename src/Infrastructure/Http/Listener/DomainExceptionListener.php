<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Listener;

use App\Domain\Tenant\Exception\TenantNotFound;
use App\Domain\User\Exception\InvalidCredentials;
use App\Domain\User\Exception\UserAlreadyExists;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class DomainExceptionListener
{
    private const MAP = [
        TenantNotFound::class => Response::HTTP_NOT_FOUND,
        UserAlreadyExists::class => Response::HTTP_CONFLICT,
        InvalidCredentials::class => Response::HTTP_UNAUTHORIZED,
        \InvalidArgumentException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
    ];

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // Let Symfony handle its own HttpExceptions
        if ($exception instanceof HttpExceptionInterface) {
            return;
        }

        $statusCode = self::MAP[$exception::class] ?? Response::HTTP_INTERNAL_SERVER_ERROR;

        $event->setResponse(new JsonResponse(
            ['error' => $exception->getMessage()],
            $statusCode,
        ));
    }
}