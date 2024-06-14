<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\Id;
use App\Domain\Shared\UpdatedAt;

interface UserRepositoryInterface {

    public function ofId(Id $id): ?User;

    public function add(User $user): void;

    public function remove(User $user): void;
}