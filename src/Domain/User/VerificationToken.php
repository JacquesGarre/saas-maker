<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Shared\TokenGeneratorInterface;

final class VerificationToken 
{    
    private function __construct(public readonly ?string $value)
    {
    }
    
    public static function generate(TokenGeneratorInterface $tokenGenerator): self 
    {   
        $value = $tokenGenerator->generate();
        return new self($value);
    }

    // TODO : unit test
    public static function fromString(string $token): self
    {
        return new self($token);
    }
}