<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\Shared\CreatedAt;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use App\Domain\User\Email;
use App\Domain\User\FirstName;
use App\Domain\User\LastName;
use App\Domain\User\User;
use App\Domain\User\PasswordHash;
use App\Tests\Stubs\Domain\Shared\IdStub;
use App\Tests\Stubs\Domain\User\PasswordHashStub;
use App\Tests\Stubs\Domain\User\UserStub;

final class UserTest extends TestCase
{
    public function testCreate(): void
    {
        $faker = Factory::create();
        $id = IdStub::random();
        $email = Email::fromString($faker->email());
        $firstName = new FirstName($faker->name());
        $lastName = new LastName($faker->name());
        $passwordHash = PasswordHashStub::random();
        $now = CreatedAt::now();
        $user = User::create(
            $id,
            $firstName,
            $lastName,
            $email,
            $passwordHash
        );
        self::assertTrue($id->equals($user->id));
        self::assertEquals($firstName->value, $user->firstName->value);
        self::assertEquals($lastName->value, $user->lastName->value);
        self::assertEquals($email->value, $user->email->value);
        self::assertEquals($passwordHash->value, $user->passwordHash->value);
        self::assertFalse($user->isVerified->value);
        self::assertEquals($now->value(), $user->createdAt->value());
        self::assertEquals($now->value(), $user->updatedAt->value());
    }

    public function testUpdate(): void
    {
        $beforeUser = UserStub::random();
        $faker = Factory::create();
        $user = $beforeUser->update(
            new FirstName($faker->name()),
            new LastName($faker->name()),
            Email::fromString($faker->email()),
            PasswordHash::fromPlainPassword("n3wP@ssw0Rd")
        );
        self::assertTrue($beforeUser->id->equals($user->id));
        self::assertNotEquals($beforeUser->firstName->value, $user->firstName->value);
        self::assertNotEquals($beforeUser->lastName->value, $user->lastName->value);
        self::assertNotEquals($beforeUser->email->value, $user->email->value);
        self::assertNotEquals($beforeUser->passwordHash->value, $user->passwordHash->value);
        self::assertEquals($beforeUser->isVerified->value, $user->isVerified->value);
        self::assertEquals($beforeUser->createdAt, $user->createdAt);
        self::assertNotEquals($beforeUser->updatedAt, $user->updatedAt);
    }

    public function testIsVerified(): void
    {
        $user = UserStub::random();
        self::assertFalse($user->isVerified());
        $user = $user->verify();
        self::assertTrue($user->isVerified());
    }

    public function testToArray(): void
    {
        $user = UserStub::random();
        $expected = [
            'id' => $user->id->value->toString(),
            'first_name' => $user->firstName->value,
            'last_name' => $user->lastName->value,
            'email' => $user->email->value,
            'is_verified' => $user->isVerified->value,
            'created_at' => $user->createdAt->value(),
            'updated_at' => $user->updatedAt->value()
        ];
        self::assertEquals($expected, $user->toArray());
    }
}