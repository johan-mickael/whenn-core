<?php

declare(strict_types=1);

namespace App\Domain\Common\Security\Authorization\Exception;

use RuntimeException;

final class ForbiddenAction extends RuntimeException {}
