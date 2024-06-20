<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Application\Application;
use App\Domain\User\User;
use App\Domain\Shared\Id;

final class ApplicationUser
{
    private function __construct(
        public readonly Id $id,
        public readonly Application $application,
        public readonly User $user
    ) {}

    public static function create(
        Application $application,
        User $user
    ): self {
        return new self(
            new Id(),
            $application,
            $user
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value->toString(),
            'application' => $this->application->toArray(),
            'user' => $this->user->toArray()
        ];
    }
}