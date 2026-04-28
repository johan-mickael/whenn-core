<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Auth;

use App\Application\Auth\Query\AuthenticateUser;
use App\Application\Auth\QueryHandler\AuthenticateUserHandler;
use App\Infrastructure\Security\JwtTokenGenerator;
use App\UI\Http\Request\Auth\LoginRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth/login', methods: ['POST'])]
final class LoginController extends AbstractController
{
    public function __construct(
        private readonly AuthenticateUserHandler $handler,
        private readonly JwtTokenGenerator $tokenGenerator,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true) ?? [];
        $dto  = LoginRequest::fromArray($body);

        $user  = ($this->handler)(new AuthenticateUser(
            tenantSlug: $dto->tenantSlug,
            email: $dto->email,
            password: $dto->password,
        ));

        return $this->json([
            'token' => $this->tokenGenerator->generateFor($user),
            'user'  => [
                'id'       => $user->getId(),
                'email'    => $user->getEmailString(),
                'role'     => $user->getRole()->value,
                'tenantId' => $user->getTenant()->getId(),
            ],
        ]);
    }
}
