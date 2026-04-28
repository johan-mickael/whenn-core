<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Tenant;

use App\Application\Tenant\Query\GetTenantBySlug;
use App\Application\Tenant\QueryHandler\GetTenantBySlugHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tenants/{slug}', methods: ['GET'])]
final class GetTenantController extends AbstractController
{
    public function __construct(
        private readonly GetTenantBySlugHandler $handler,
    ) {}

    public function __invoke(string $slug): JsonResponse
    {
        $tenant = ($this->handler)(new GetTenantBySlug($slug));

        return $this->json([
            'id'      => $tenant->getId(),
            'name'    => $tenant->getName(),
            'slug'    => $tenant->getSlug(),
            'logoUrl' => $tenant->getLogoUrl(),
        ]);
    }
}
