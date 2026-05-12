<?php


declare(strict_types=1);

namespace App\UI\Http\Controller;

use App\Domain\Common\Security\Authorization\UserContext;
use App\Infrastructure\Security\User\SymfonySecurityUser;
use App\UI\Http\Exception\InvalidAuthenticatedUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract
class HttpController extends AbstractController {
    protected
    function getUserContext(): UserContext {
        $user = $this->getUser();

        if (!$user instanceof SymfonySecurityUser) {
            throw new InvalidAuthenticatedUser;
        }

        return $user->getUserContext();
    }
}
