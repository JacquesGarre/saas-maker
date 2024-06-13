<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\Id;
use App\Domain\Shared\UpdatedAt;

interface UserRepositoryInterface {

    public function ofId(Id $id): User;

    public function add(User $user): void;

    public function remove(User $user): void;

    public function findBy(
        ?FirstName $firstName = null, 
        ?LastName $lastName = null, 
        ?Email $email = null,
        ?IsVerified $isVerified = null,
        ?CreatedAt $createdBefore = null,
        ?CreatedAt $createdAfter = null,
        ?UpdatedAt $updatedBefore = null,
        ?UpdatedAt $updatedAfter = null,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array;

}