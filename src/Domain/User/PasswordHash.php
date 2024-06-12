<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\Exception\InvalidPasswordException;

final class PasswordHash {

    public const PASSWORD_REGEX = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/"; 

    private function __construct(public readonly string $value) 
    {
    }

    public static function fromPlainPassword(string $value): self
    {
        self::assertValid($value);
        $value = password_hash($value, PASSWORD_DEFAULT);
        return new self($value);
    }

    public static function assertValid(string $value): void
    {
        if (!preg_match(self::PASSWORD_REGEX, $value)) {
            throw new InvalidPasswordException(
                "Password must be at least 8 characters, 1 uppercase, 1 lowercase, 1 digit and 1 special char"
            );
        }
    }

    public function matches(string $value): bool
    {
        return password_verify($value, $this->value);
    }
}
