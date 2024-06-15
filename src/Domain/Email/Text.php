<?php

declare(strict_types=1);

namespace App\Domain\Email;

final class Text {
    public function __construct(public readonly string $value)
    {
        
    }
}