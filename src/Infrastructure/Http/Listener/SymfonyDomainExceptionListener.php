<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Listener;

use App\Domain\User\Exception\InvalidCredentials;
use App\Domain\User\Exception\UserAlreadyExists;
use App\Domain\Venue\Exception\DuplicateVenueName;
use App\Domain\Venue\Exception\VenueNotFound;
use InvalidArgumentException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class SymfonyDomainExceptionListener
{
    private const array MAP = [
        UserAlreadyExists::class => Response::HTTP_CONFLICT,
        VenueNotFound::class => Response::HTTP_NOT_FOUND,
        DuplicateVenueName::class => Response::HTTP_CONFLICT,
        InvalidCredentials::class => Response::HTTP_UNAUTHORIZED,
        InvalidArgumentException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
        AccessDeniedException::class => Response::HTTP_FORBIDDEN,
    ];

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $statusCode = match (true) {
            $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
            isset(self::MAP[$exception::class]) => self::MAP[$exception::class],
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };

        $event->setResponse(
            new Response(
                $exception->getMessage(),
                $statusCode,
            ),
        );
    }
}
