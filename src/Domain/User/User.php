<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\Id;
use App\Domain\Shared\UpdatedAt;

final class User {

    private function __construct(
        public readonly Id $id,
        public readonly FirstName $firstName,
        public readonly LastName $lastName,
        public readonly Email $email,
        public readonly PasswordHash $passwordHash,
        public readonly IsVerified $isVerified,
        public readonly CreatedAt $createdAt,
        public readonly UpdatedAt $updatedAt
    ) {  
    }

    public static function create(
        Id $id,
        FirstName $firstName,
        LastName $lastName,
        Email $email,
        PasswordHash $passwordHash
    ): self {
        $user = new self(
            $id,
            $firstName,
            $lastName,
            $email,
            $passwordHash,
            new IsVerified(false),
            CreatedAt::now(),
            UpdatedAt::now()
        );
        return $user;
    }

    public function update(
        FirstName $firstName,
        LastName $lastName,
        Email $email,
        PasswordHash $passwordHash
    ): self {
        $user = new self(
            $this->id,
            $firstName,
            $lastName,
            $email,
            $passwordHash,
            $this->isVerified,
            $this->createdAt,
            UpdatedAt::now()
        );
        return $user;
    }

    public function verify(): self
    {
        $user = new self(
            $this->id,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->passwordHash,
            new IsVerified(true),
            $this->createdAt,
            UpdatedAt::now()
        );
        return $user;
    }

    public function isVerified(): bool
    {
        return $this->isVerified->value;
    }

}