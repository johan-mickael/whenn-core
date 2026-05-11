<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Auth;

use App\Application\Auth\Command\RegisterUser;
use App\Application\Auth\CommandHandler\RegisterUserHandler;
use App\UI\Http\Request\Auth\RegisterRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth/register', methods: ['POST'])]
final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly RegisterUserHandler $handler,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] RegisterRequest $dto,
    ): JsonResponse
    {
        $user = ($this->handler)(new RegisterUser(
            tenantSlug: $dto->tenant_slug,
            email: $dto->email,
            password: $dto->password,
            firstName: $dto->first_name,
            lastName: $dto->last_name,
        ));

        return $this->json([
            'id'        => $user->id(),
            'email'     => $user->email(),
            'role'      => $user->role()->value,
            'firstName' => $user->firstName(),
            'lastName'  => $user->lastName(),
        ], Response::HTTP_CREATED);
    }
}
