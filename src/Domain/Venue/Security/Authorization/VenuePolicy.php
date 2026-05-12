<?php

declare(strict_types=1);

namespace App\Domain\Venue\Security\Authorization;

use App\Domain\Common\Security\Authorization\Action;
use App\Domain\Common\Security\Authorization\PolicyInterface;
use App\Domain\Common\Security\Authorization\UserContext;

final class VenuePolicy implements PolicyInterface
{
    public function supports(
        object $subject,
    ): bool {
        return $subject instanceof CreateVenue;
    }

    public function authorize(
        UserContext $actor,
        Action $action,
        object $subject,
    ): bool {
        if (!$subject instanceof CreateVenue) {
            return false;
        }

        if ($action !== Action::CREATE) {
            return false;
        }

        return $actor->role->isStaff();
    }
}
