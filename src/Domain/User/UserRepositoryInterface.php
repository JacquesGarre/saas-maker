<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Auth\Jwt;
use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\Id;
use App\Domain\Shared\UpdatedAt;

interface UserRepositoryInterface {

    public function ofId(Id $id): ?User;

    public function add(User $user): void;

    public function remove(User $user): void;

    public function findOneByEmailOrId(Email $email, Id $id): ?User;

    public function findOneByEmail(Email $email): ?User;

    public function findOneByJwt(Jwt $jwt): ?User;
}