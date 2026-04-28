<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Auth;

use App\Application\Auth\Command\RegisterUser;
use App\Application\Auth\CommandHandler\RegisterUserHandler;
use App\Domain\Tenant\Exception\TenantNotFound;
use App\Domain\User\Exception\UserAlreadyExists;
use App\UI\Http\Request\Auth\RegisterRequest;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/auth/register', methods: ['POST'])]
final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly RegisterUserHandler $handler,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true) ?? [];
        $dto = RegisterRequest::fromArray($body);

        $violations = $this->validator->validate($dto);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            return $this->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $user = ($this->handler)(new RegisterUser(
                tenantSlug: $dto->tenantSlug,
                email: $dto->email,
                password: $dto->password,
                firstName: $dto->firstName,
                lastName: $dto->lastName,
            ));
        } catch (TenantNotFound $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (UserAlreadyExists $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_CONFLICT);
        } catch (InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmailString(),
            'role' => $user->getRole()->value,
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
        ], Response::HTTP_CREATED);
    }
}
