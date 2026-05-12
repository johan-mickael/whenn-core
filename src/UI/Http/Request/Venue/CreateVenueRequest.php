<?php

declare(strict_types=1);

namespace App\UI\Http\Request\Venue;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateVenueRequest
{
    #[Assert\NotBlank(message: 'name is required.')]
    public ?string $name = '';

    #[Assert\NotBlank(message: 'street is required.')]
    public ?string $street = '';

    #[Assert\NotBlank(message: 'zip code is required.')]
    public ?string $zipCode;

    #[Assert\NotBlank(message: 'city is required.')]
    public ?string $city = '';

    #[Assert\NotBlank(message: 'country is required.')]
    public ?string $country = '';

    #[Assert\NotBlank(message: 'capacity is required.')]
    public ?int $capacity = 0;

    #[Assert\NotBlank(message: 'latitude is required.')]
    public ?float  $latitude  = null;

    #[Assert\NotBlank(message: 'longitude is required.')]
    public ?float  $longitude = null;
}
