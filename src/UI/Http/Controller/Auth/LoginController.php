<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Auth;

use App\Application\Auth\Command\LoginCommand;
use App\Application\Auth\CommandHandler\LoginUseCase;
use App\UI\Http\Request\Auth\LoginRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth/login', methods: ['POST'])]
final class LoginController extends AbstractController
{
    public function __construct(
        private readonly LoginUseCase $loginUseCase,
    ) {}

    public function __invoke(
        #[MapRequestPayload] LoginRequest $dto,
    ): JsonResponse {
        $userToken = ($this->loginUseCase)(new LoginCommand(
            email: $dto->email,
            password: $dto->password,
        ));

        return $this->json(['token' => $userToken]);
    }
}
