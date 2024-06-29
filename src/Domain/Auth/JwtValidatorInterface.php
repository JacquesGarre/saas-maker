<?php

declare(strict_types=1);

namespace App\Domain\Auth;

interface JwtValidatorInterface {

    public function validate(string $jwt): void;
}