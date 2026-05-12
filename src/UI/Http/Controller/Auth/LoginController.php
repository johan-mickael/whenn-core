<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Auth;

use App\Application\Auth\Query\AuthenticateUser;
use App\Application\Auth\QueryHandler\AuthenticateUserHandler;
use App\Infrastructure\Security\User\Jwt\LexikJwtTokenGenerator;
use App\UI\Http\Request\Auth\LoginRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth/login', methods: ['POST'])]
final class LoginController extends AbstractController
{
    public function __construct(
        private readonly AuthenticateUserHandler $handler,
        private readonly LexikJwtTokenGenerator  $tokenGenerator,
    ) {}

    public function __invoke(
        #[MapRequestPayload] LoginRequest $dto,
    ): JsonResponse {
        $user = ($this->handler)(new AuthenticateUser(
            email: $dto->email,
            password: $dto->password,
        ));

        return $this->json([
            'token' => $this->tokenGenerator->generateFor($user),
        ]);
    }
}
