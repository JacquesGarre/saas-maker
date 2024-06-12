<?php

declare(strict_types=1);

namespace App\Domain\User;

final class IsVerified {

    public function __construct(public readonly bool $value) 
    {
    }
}
