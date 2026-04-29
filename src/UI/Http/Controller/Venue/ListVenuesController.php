<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Venue;

use App\Application\Venue\Query\ListVenues;
use App\Application\Venue\QueryHandler\ListVenuesHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/venues', methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class ListVenuesController extends AbstractController
{
    public function __construct(
        private readonly ListVenuesHandler $handler,
    ) {}

    public function __invoke(): JsonResponse
    {
        $user   = $this->getUser();
        $venues = ($this->handler)(new ListVenues(
            tenantId: $user->getTenant()->getId(),
        ));

        return $this->json(array_map(fn($venue) => [
            'id'        => $venue->getId(),
            'name'      => $venue->getName(),
            'city'      => $venue->getCity(),
            'country'   => $venue->getCountry(),
            'capacity'  => $venue->getCapacity(),
        ], $venues));
    }
}
