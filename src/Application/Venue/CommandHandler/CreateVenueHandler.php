<?php

declare(strict_types=1);

namespace App\Application\Venue\CommandHandler;

use App\Application\Venue\Command\CreateVenue;
use App\Domain\Common\Id\IdGeneratorInterface;
use App\Domain\Common\Security\Authorization\Action;
use App\Domain\Common\Security\Authorization\AuthorizationServiceInterface;
use App\Domain\Common\Security\Authorization\UserContext;
use App\Domain\Common\Transaction\TransactionManagerInterface;
use App\Domain\Venue\Exception\CreateVenueForbidden;
use App\Domain\Venue\Security\Authorization\CreateVenue as CreateVenueAuthorization;
use App\Domain\Venue\Service\VenueAddressMustBeUnique;
use App\Domain\Venue\Venue;
use App\Domain\Venue\VenueRepositoryInterface;

final readonly
class CreateVenueHandler
{
    public function __construct(
        private VenueRepositoryInterface      $venues,
        private VenueAddressMustBeUnique      $venueAddressMustBeUnique,
        private TransactionManagerInterface   $transaction,
        private IdGeneratorInterface          $idGenerator,
        private AuthorizationServiceInterface $authorization,
    )
    {
    }

    public function __invoke(
        CreateVenue $command,
        UserContext $actor,
    ): Venue
    {

        if (!$this->authorization->authorize(
            $actor,
            Action::CREATE,
            new CreateVenueAuthorization(),
        )) {
            throw new CreateVenueForbidden();
        }

        $venue = Venue::create(
            id: $this->idGenerator->generate(),
            name: $command->name,
            address: $command->address,
            city: $command->city,
            country: $command->country,
            capacity: $command->capacity,
            zipCode: $command->zipCode,
            latitude: $command->latitude,
            longitude: $command->longitude,
        );

        $this->venueAddressMustBeUnique->check($venue);

        $this->venues->save($venue);
        $this->transaction->flush();

        return $venue;
    }
}
