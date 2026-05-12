<?php


namespace App\UI\Http\Exception;

use LogicException;

class InvalidAuthenticatedUser extends LogicException {
    public
    function __construct() {
        parent::__construct('Invalid authenticated user.');
    }
}
