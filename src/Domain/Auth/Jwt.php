<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\User\User;

final class Jwt {
    
    private function __construct(public readonly string $value)
    {
    }

    public static function fromUser(
        JwtGeneratorInterface $jwtGenerator,
        User $user
    ): self {   
        $value = $jwtGenerator->fromUser($user);
        return new self($value);
    }

    public static function fromString(JwtValidatorInterface $validator, string $token): self
    {   
        $validator->validate($token);
        return new self($token);
    }
}