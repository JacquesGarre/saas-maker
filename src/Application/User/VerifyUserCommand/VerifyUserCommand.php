<?php

declare(strict_types=1);

namespace App\Application\User\VerifyUserCommand;

use App\Domain\Shared\CommandInterface;

final class VerifyUserCommand implements CommandInterface
{
    public function __construct(
        public readonly ?string $id = null
    ) {
    }
}