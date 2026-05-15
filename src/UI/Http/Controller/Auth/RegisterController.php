<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Auth;

use App\Application\Auth\Command\RegisterUserCommand;
use App\Application\Auth\CommandHandler\RegisterUserUseCase;
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
        private readonly RegisterUserUseCase $registerUserUseCase,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] RegisterRequest $dto,
    ): JsonResponse
    {
        $registerUserResult = ($this->registerUserUseCase)(new RegisterUserCommand(
            email: $dto->email,
            password: $dto->password,
            firstName: $dto->first_name,
            lastName: $dto->last_name,
        ));

        return $this->json($registerUserResult, Response::HTTP_CREATED);
    }
}
