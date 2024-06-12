<?php

declare(strict_types=1);

namespace App\Domain\User;

final class FirstName {

    public function __construct(public readonly string $value) 
    {
    }
}
