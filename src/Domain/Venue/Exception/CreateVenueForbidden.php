<?php

namespace App\Domain\Venue\Exception;
use DomainException;

final class CreateVenueForbidden extends DomainException {
    public function __construct() {
        parent::__construct('Create venue is not allowed.');
    }
}
