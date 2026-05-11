<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Tenant;

use App\Application\Tenant\Command\CreateTenant;
use App\Application\Tenant\CommandHandler\CreateTenantHandler;
use App\Domain\Tenant\ValueObject\Slug;
use App\UI\Http\Request\Tenant\CreateTenantRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tenants', methods: ['POST'])]
final class CreateTenantController extends AbstractController
{
    public function __construct(
        private readonly CreateTenantHandler $handler,
    ) {}

    public function __invoke(
        #[MapRequestPayload] CreateTenantRequest $dto,
    ): JsonResponse {
        $tenant = ($this->handler)(new CreateTenant(
            name: $dto->name,
            slug: new Slug($dto->slug),
            logoUrl: $dto->logoUrl,
        ));

        return $this->json([
            'id'      => $tenant->id(),
            'name'    => $tenant->name(),
            'slug'    => (string) $tenant->slug(),
            'logoUrl' => $tenant->logoUrl(),
        ], Response::HTTP_CREATED);
    }
}
