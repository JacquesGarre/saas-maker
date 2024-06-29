<?php

declare(strict_types=1);

namespace App\Domain\Shared;

interface TokenGeneratorInterface {

    public function generate(): string;
}