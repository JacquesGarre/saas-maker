<?php

declare(strict_types=1);

namespace App\Application\Application\CreateApplicationUserCommand;

use App\Domain\Shared\CommandInterface;

// TODO : TEST THIS + ADD VALIDATOR + TEST VALIDATOR!
final class CreateApplicationUserCommand implements CommandInterface
{
    public function __construct(
        public readonly ?string $applicationId = null,
        public readonly ?string $email = null,
        public readonly ?string $invitedById = null
    ) {
    }
}