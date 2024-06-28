<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\User\User;
use App\Domain\Auth\Exception\InvalidJwtException;

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

    // TODO: Unit test this
    public static function fromToken(JwtValidatorInterface $validator, string $token): self
    {   
        if (!$validator->validate($token)) {
            throw new InvalidJwtException("Invalid jwt token");
        }
        return new self($token);
    }
}