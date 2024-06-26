<?php

declare(strict_types=1);

namespace App\Application\User\CreateUserCommand;

use App\Domain\Shared\CommandInterface;

final class CreateUserCommand implements CommandInterface
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null
    ) {
    }
}