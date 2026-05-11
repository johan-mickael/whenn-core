<?php

declare(strict_types=1);

namespace App\Application\Venue\CommandHandler;

use App\Domain\Common\TransactionManagerInterface;
use App\Domain\Venue\Service\VenueAddressMustBeUnique;
use App\Domain\Venue\Venue;
use App\Domain\Venue\VenueRepositoryInterface;
use App\Application\Venue\Command\CreateVenue;

final class CreateVenueHandler
{
    public function __construct(
        private readonly VenueRepositoryInterface $venues,
        private readonly VenueAddressMustBeUnique $venueAddressMustBeUnique,
        private readonly TransactionManagerInterface $transaction,
    ) {}

    public function __invoke(CreateVenue $command): Venue
    {

        $venue = new Venue(
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
