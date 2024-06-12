<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\DomainEventsTrait;
use App\Domain\Shared\Id;
use App\Domain\Shared\UpdatedAt;

final class User {

    use DomainEventsTrait;

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
        $this->initDomainEventCollection();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value->toString(),
            'first_name' => $this->firstName->value,
            'last_name' => $this->lastName->value,
            'email' => $this->email->value,
            'is_verified' => $this->isVerified->value,
            'created_at' => $this->createdAt->value(),
            'updated_at' => $this->updatedAt->value()
        ];
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
        $user->notifyDomainEvent(UserCreatedDomainEvent::fromUser($user));
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
        $user->notifyDomainEvent(UserUpdatedDomainEvent::fromUser($user));
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
        $user->notifyDomainEvent(UserVerifiedDomainEvent::fromUser($user));
        return $user;
    }

    public function isVerified(): bool
    {
        return $this->isVerified->value;
    }

}