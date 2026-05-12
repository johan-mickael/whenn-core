<?php

namespace App\Domain\Venue\Exception;
use DomainException;

final class ListVenuesForbidden extends DomainException {
    public function __construct() {
        parent::__construct('List venues is not allowed.');
    }
}
