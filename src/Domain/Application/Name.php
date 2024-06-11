<?php

declare(strict_types=1);

namespace App\Domain\Application;

final class Name {

    public function __construct(public readonly string $value) 
    {
    }
}
