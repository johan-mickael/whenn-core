<?php

declare(strict_types=1);

namespace App\UI\Http\Request\Venue;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateVenueRequest
{
    #[Assert\NotBlank(message: 'name is required.')]
    public ?string $name = '';

    #[Assert\NotBlank(message: 'address is required.')]
    public ?string $address = '';

    #[Assert\NotBlank(message: 'city is required.')]
    public ?string $city = '';

    #[Assert\NotBlank(message: 'country is required.')]
    public ?string $country = '';

    #[Assert\NotBlank(message: 'capacity is required.')]
    public ?int $capacity = 0;

    public ?string $zipCode   = null;
    public ?float  $latitude  = null;
    public ?float  $longitude = null;
}
