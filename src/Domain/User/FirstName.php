<?php

declare(strict_types=1);

namespace App\Domain\User;

final class FirstName {

    public function __construct(public readonly string $value) 
    {
    }

    // TODO : TEST THIS
    public static function empty(): self
    {
        return new self('');
    }
}
