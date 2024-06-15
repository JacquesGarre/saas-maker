<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\User\User;

final class Jwt {
    
    public function __construct(public readonly string $value)
    {
    }

    public static function fromUser(
        JwtGeneratorInterface $jwtGenerator,
        User $user
    ): self {   
        $value = $jwtGenerator->fromUser($user);
        return new self($value);
    }
}