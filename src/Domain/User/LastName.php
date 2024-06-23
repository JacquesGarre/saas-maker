<?php

declare(strict_types=1);

namespace App\Domain\User;

final class LastName {

    public function __construct(public readonly string $value) 
    {
    }

    public static function empty(): self
    {
        return new self('');
    }
}
