<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\Exception\InvalidPasswordException;

final class PasswordHash {

    public const PASSWORD_REGEX = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/"; 

    private function __construct(public readonly string $value) 
    {
    }

    // TODO : TEST THIS
    public static function generate(): self
    {
        $plainPassword = self::generatePlainPassword();
        return self::fromPlainPassword($plainPassword);
    }

    // TODO : TEST THIS
    public static function generatePlainPassword(): string
    {
        $upperCase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerCase = 'abcdefghijklmnopqrstuvwxyz';
        $digits = '0123456789';
        $specialChars = '!@#$%^&*()-_=+<>?';
        $allChars = $upperCase.$lowerCase.$digits.$specialChars;
        $password = '';
        $password .= $upperCase[random_int(0, strlen($upperCase) - 1)];
        $password .= $lowerCase[random_int(0, strlen($lowerCase) - 1)];
        $password .= $digits[random_int(0, strlen($digits) - 1)];
        $password .= $specialChars[random_int(0, strlen($specialChars) - 1)];
        for ($i = 4; $i < 8; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        $password = str_shuffle($password);
        return $password;
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
