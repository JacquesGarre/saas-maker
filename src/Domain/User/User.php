<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Auth\Jwt;
use App\Domain\Auth\JwtGeneratorInterface;
use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\DomainEventsTrait;
use App\Domain\Shared\Id;
use App\Domain\Shared\UpdatedAt;
use App\Domain\User\Exception\InvalidPasswordException;
use App\Domain\User\Exception\UserNotVerifiedException;

final class User {

    use DomainEventsTrait;

    private function __construct(
        public readonly Id $id,
        private FirstName $firstName,
        private LastName $lastName,
        private Email $email,
        private PasswordHash $passwordHash,
        private IsVerified $isVerified,
        public readonly CreatedAt $createdAt,
        private UpdatedAt $updatedAt,
        private ?Jwt $jwt = null
    ) {  
        $this->initDomainEventCollection();
    }

    public function firstName(): FirstName
    {
        return $this->firstName;
    }

    public function lastName(): LastName
    {
        return $this->lastName;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function passwordHash(): PasswordHash
    {
        return $this->passwordHash;
    }

    public function isVerified(): IsVerified
    {
        return $this->isVerified;
    }

    public function updatedAt(): UpdatedAt
    {
        return $this->updatedAt;
    }

    public function jwt(): ?Jwt
    {
        return $this->jwt ?? null;
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
    ): void {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->updatedAt = UpdatedAt::now();
        $this->notifyDomainEvent(UserUpdatedDomainEvent::fromUser($this));
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

    public function login(
        JwtGeneratorInterface $jwtGenerator,
        string $password
    ): void {
        if (!$this->passwordHash->matches($password)) {
            throw new InvalidPasswordException("Wrong password");
        }
        if (!$this->isVerified->value) {
            throw new UserNotVerifiedException("User is not verified");
        }
        $this->updatedAt = UpdatedAt::now();
        $this->jwt = Jwt::fromUser($jwtGenerator, $this);
        $this->notifyDomainEvent(UserLoggedInDomainEvent::fromUser($this));
    }
}