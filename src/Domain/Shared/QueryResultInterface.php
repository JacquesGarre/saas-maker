<?php

declare(strict_types=1);

namespace App\Domain\Shared;

interface QueryResultInterface {
    public function toArray(): array;
}