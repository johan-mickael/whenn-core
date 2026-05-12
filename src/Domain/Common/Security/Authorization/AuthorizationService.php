<?php

declare(strict_types=1);

namespace App\Domain\Common\Security\Authorization;

final readonly class AuthorizationService implements AuthorizationServiceInterface
{
    /**
     * @param iterable<PolicyInterface> $policies
     */
    public function __construct(
        private iterable $policies,
    ) {}

    public function authorize(
        UserContext $actor,
        Action $action,
        object $resource,
    ): bool {
        foreach ($this->policies as $policy) {
            if ($policy->supports($resource)) {
                return $policy->authorize(
                    $actor,
                    $action,
                    $resource,
                );
            }
        }

        return false;
    }
}
