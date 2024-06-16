<?php

declare(strict_types=1);

namespace App\Application\Application\CreateApplicationCommand;

use App\Domain\Shared\CommandInterface;

final class CreateApplicationCommand implements CommandInterface
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?string $name = null,
        public readonly ?string $subdomain = null,
        public readonly ?string $createdById = null
    ) {
    }
}