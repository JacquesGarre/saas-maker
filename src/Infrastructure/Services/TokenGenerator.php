<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Domain\Shared\TokenGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface as GeneratorInterface;

final class TokenGenerator implements TokenGeneratorInterface {

    public function __construct(private readonly GeneratorInterface $generator)
    {
    }

    public function generate(): string
    {
        return $this->generator->generateToken();
    }
}