<?php

declare(strict_types=1);

namespace App\Application\Auth\LoginCommand;

use App\Domain\Shared\CommandInterface;

final class LoginCommand implements CommandInterface
{
    public function __construct(
        public readonly ?string $email = null,
        public readonly ?string $password = null
    ) {
    }
}