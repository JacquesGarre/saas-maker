<?php

declare(strict_types=1);

namespace App\Domain\Auth;

interface JwtValidatorInterface {

    public function assertValid(Jwt $jwt): void;
}