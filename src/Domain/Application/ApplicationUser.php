<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Application\Application;
use App\Domain\Shared\Id;
use App\Domain\User\User;

final class ApplicationUser {

    private function __construct(
        public readonly Id $id,
        public readonly Application $application,
        public readonly User $user
    ) {
    }

    public function toArray(): array
    {
        return [
            'user' => $this->user->toArray(),
            //'role => ''
        ];
    }

    public static function create(
        Application $application,
        User $user
    ): self {
        $applicationUser = new self(
            new Id(),
            $application,
            $user
        );
        return $applicationUser;
    }
}