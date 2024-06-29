<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Application\Application;
use App\Domain\Auth\Jwt;
use App\Domain\Auth\JwtGeneratorInterface;
use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\DomainEventsTrait;
use App\Domain\Shared\Id;
use App\Domain\Shared\UpdatedAt;
use App\Domain\Shared\EmailAddress;
use App\Domain\Shared\TokenGeneratorInterface;
use App\Domain\User\Exception\InvalidPasswordException;
use App\Domain\User\Exception\UserNotVerifiedException;
use App\Domain\User\Exception\PermissionNotAllowedException;

class User {

    use DomainEventsTrait;

    private function __construct(
        private Id $id,
        private FirstName $firstName,
        private LastName $lastName,
        private EmailAddress $email,
        private PasswordHash $passwordHash,
        private IsVerified $isVerified,
        private CreatedAt $createdAt,
        private UpdatedAt $updatedAt,
        private ?Jwt $jwt = null,
        private ?VerificationToken $verificationToken = null
    ) {  
        $this->initDomainEventCollection();
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function firstName(): FirstName
    {
        return $this->firstName;
    }

    public function lastName(): LastName
    {
        return $this->lastName;
    }

    public function email(): EmailAddress
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

    public function createdAt(): CreatedAt
    {
        return $this->createdAt;
    }

    public function jwt(): ?Jwt
    {
        return $this->jwt ?? null;
    }

    public function verificationToken(): ?VerificationToken
    {
        return $this->verificationToken ?? null;
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
            'updated_at' => $this->updatedAt->value(),
            'verification_token' => $this->verificationToken?->value
        ];
    }

    public static function create(
        Id $id,
        FirstName $firstName,
        LastName $lastName,
        EmailAddress $email,
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

    public static function fromEmail(EmailAddress $email): self 
    {
        $user = new self(
            new Id(),
            FirstName::empty(),
            LastName::empty(),
            $email,
            PasswordHash::generate(),
            new IsVerified(true),
            CreatedAt::now(),
            UpdatedAt::now()
        );
        $user->notifyDomainEvent(UserCreatedDomainEvent::fromUser($user));
        return $user;
    }

    public function update(
        User $updatedBy,
        ?FirstName $firstName = null,
        ?LastName $lastName = null,
        ?EmailAddress $email = null,
        ?PasswordHash $passwordHash = null
    ): void {
        if (!$updatedBy->id->equals($this->id)) {
            throw new PermissionNotAllowedException("User is not allowed to perform this action");
        }
        $this->firstName = $firstName ?? $this->firstName;
        $this->lastName = $lastName ?? $this->lastName;
        $this->email = $email ?? $this->email;
        $this->passwordHash = $passwordHash ?? $this->passwordHash;
        $this->updatedAt = UpdatedAt::now();
        $this->notifyDomainEvent(UserUpdatedDomainEvent::fromUser($this));
    }

    public function fullName(): string 
    {
        return $this->firstName->value.' '.$this->lastName->value;
    }

    public function generateVerificationToken(
        TokenGeneratorInterface $tokenGenerator
    ): void {
        $this->verificationToken = VerificationToken::generate($tokenGenerator);
        $this->updatedAt = UpdatedAt::now();
        $this->notifyDomainEvent(UserVerificationTokenGeneratedDomainEvent::fromUser($this));
    }

    public function verify(): void
    {
        $this->isVerified = new IsVerified(true);
        $this->updatedAt = UpdatedAt::now();
        $this->notifyDomainEvent(UserVerifiedDomainEvent::fromUser($this));
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