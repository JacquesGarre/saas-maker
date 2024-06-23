<?php

declare(strict_types= 1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\User\Exception\InvalidPasswordException;
use App\Domain\User\PasswordHash;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class PasswordHashTest extends TestCase
{
    public function testConstructor(): void
    {
        $value = "p@ssW0Rd";
        $passwordHash = PasswordHash::fromPlainPassword($value);
        self::assertTrue(password_verify($value, $passwordHash->value)); 
    }

    public function testAssertValid(): void
    {
        $value = "notstrong";
        $this->expectException(InvalidPasswordException::class);
        PasswordHash::fromPlainPassword($value);
    }

    public function testMatches(): void
    {
        $value = "p@ssW0Rd";
        $wrongValue = "not password";
        $passwordHash = PasswordHash::fromPlainPassword($value);
        self::assertTrue($passwordHash->matches($value));
        self::assertFalse($passwordHash->matches($wrongValue));
    }

    public function testGeneratePlainPassword(): void
    {
        $password = PasswordHash::generatePlainPassword();
        self::assertTrue(strlen($password)>8);
        self::assertNull(PasswordHash::assertValid($password));
    }
}