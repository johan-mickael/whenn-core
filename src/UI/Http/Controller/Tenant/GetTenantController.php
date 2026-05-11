<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Tenant;

use App\Application\Tenant\Query\GetTenantBySlug;
use App\Application\Tenant\QueryHandler\GetTenantBySlugHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/tenants/{slug}', methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class GetTenantController extends AbstractController
{
    public function __construct(
        private readonly GetTenantBySlugHandler $handler,
    ) {}

    public function __invoke(string $slug): JsonResponse
    {
        $tenant = ($this->handler)(new GetTenantBySlug($slug));

        return $this->json([
            'id'      => $tenant->id(),
            'name'    => $tenant->name(),
            'slug'    => (string) $tenant->slug(),
            'logoUrl' => $tenant->logoUrl(),
        ]);
    }
}
