<?php

namespace App\Domain\Event\Exception;

use DomainException;

final class CreateEventForbidden extends DomainException
{
    public function __construct()
    {
        parent::__construct('Create event is not allowed.');
    }
}
