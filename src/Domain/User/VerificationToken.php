<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Auth\JwtGeneratorInterface;

final class VerificationToken 
{    
    private function __construct(public readonly ?string $value)
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