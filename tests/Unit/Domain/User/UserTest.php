<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\Auth\JwtGeneratorInterface;
use App\Domain\Shared\CreatedAt;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use App\Domain\User\Email;
use App\Domain\User\FirstName;
use App\Domain\User\LastName;
use App\Domain\User\User;
use App\Domain\User\PasswordHash;
use App\Domain\User\UserCreatedDomainEvent;
use App\Domain\User\UserUpdatedDomainEvent;
use App\Domain\User\UserVerifiedDomainEvent;
use App\Domain\User\UserLoggedInDomainEvent;
use App\Tests\Stubs\Domain\Shared\IdStub;
use App\Tests\Stubs\Domain\User\PasswordHashStub;
use App\Tests\Stubs\Domain\User\UserStub;
use App\Domain\User\Exception\InvalidPasswordException;
use App\Domain\User\Exception\UserNotVerifiedException;

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
        self::assertEquals($firstName->value, $user->firstName()->value);
        self::assertEquals($lastName->value, $user->lastName()->value);
        self::assertEquals($email->value, $user->email()->value);
        self::assertEquals($passwordHash->value, $user->passwordHash()->value);
        self::assertFalse($user->isVerified()->value);
        self::assertEquals($now->value(), $user->createdAt->value());
        self::assertEquals($now->value(), $user->updatedAt()->value());
        self::assertCount(1, $user->domainEvents);
        self::assertInstanceOf(UserCreatedDomainEvent::class, $user->domainEvents->last());
    }

    public function testUpdate(): void
    {
        $beforeUser = UserStub::random();
        $faker = Factory::create();
        $user = clone $beforeUser;
        $user->update(
            $beforeUser,
            new FirstName($faker->name()),
            new LastName($faker->name()),
            Email::fromString($faker->email()),
            PasswordHash::fromPlainPassword("n3wP@ssw0Rd")
        );
        self::assertTrue($beforeUser->id->equals($user->id));
        self::assertNotEquals($beforeUser->firstName()->value, $user->firstName()->value);
        self::assertNotEquals($beforeUser->lastName()->value, $user->lastName()->value);
        self::assertNotEquals($beforeUser->email()->value, $user->email()->value);
        self::assertNotEquals($beforeUser->passwordHash()->value, $user->passwordHash()->value);
        self::assertEquals($beforeUser->isVerified()->value, $user->isVerified()->value);
        self::assertEquals($beforeUser->createdAt, $user->createdAt);
        self::assertNotEquals($beforeUser->updatedAt(), $user->updatedAt());
        self::assertCount(1, $user->domainEvents);
        self::assertInstanceOf(UserUpdatedDomainEvent::class, $user->domainEvents->last());
    }

    public function testUpdateWithNulls(): void
    {
        $beforeUser = UserStub::random();
        $user = clone $beforeUser;
        $user->update(
            $beforeUser,
            null,
            null,
            null,
            null
        );
        self::assertTrue($beforeUser->id->equals($user->id));
        self::assertEquals($beforeUser->firstName()->value, $user->firstName()->value);
        self::assertEquals($beforeUser->lastName()->value, $user->lastName()->value);
        self::assertEquals($beforeUser->email()->value, $user->email()->value);
        self::assertEquals($beforeUser->passwordHash()->value, $user->passwordHash()->value);
        self::assertEquals($beforeUser->isVerified()->value, $user->isVerified()->value);
        self::assertEquals($beforeUser->createdAt, $user->createdAt);
        self::assertNotEquals($beforeUser->updatedAt(), $user->updatedAt());
        self::assertCount(1, $user->domainEvents);
        self::assertInstanceOf(UserUpdatedDomainEvent::class, $user->domainEvents->last());
    }

    public function testVerify(): void
    {
        $user = UserStub::random();
        self::assertFalse($user->isVerified()->value);
        $user->verify();
        self::assertCount(1, $user->domainEvents);
        self::assertInstanceOf(UserVerifiedDomainEvent::class, $user->domainEvents->last());
        self::assertTrue($user->isVerified()->value);
    }

    public function testToArray(): void
    {
        $user = UserStub::random();
        $expected = [
            'id' => $user->id->value->toString(),
            'first_name' => $user->firstName()->value,
            'last_name' => $user->lastName()->value,
            'email' => $user->email()->value,
            'is_verified' => $user->isVerified()->value,
            'created_at' => $user->createdAt->value(),
            'updated_at' => $user->updatedAt()->value()
        ];
        self::assertEquals($expected, $user->toArray());
    }

    public function testLoginSunnyCase(): void
    {
        $password = 'p@ssW0rd';
        $user = UserStub::randomVerified($password);
        $jwtGenerator = $this->createMock(JwtGeneratorInterface::class);
        $user->login($jwtGenerator, $password);
        self::assertCount(1, $user->domainEvents);
        self::assertInstanceOf(UserLoggedInDomainEvent::class, $user->domainEvents->last());
        self::assertTrue($user->isVerified()->value);
        self::assertNotNull($user->jwt());
    }

    public function testLoginWrongPassword(): void
    {
        $password = 'p@ssW0rd';
        $wrongPassword = Factory::create()->password();
        $user = UserStub::randomVerified($password);
        $jwtGenerator = $this->createMock(JwtGeneratorInterface::class);
        $this->expectException(InvalidPasswordException::class);
        $this->expectExceptionMessage("Wrong password");
        $user->login($jwtGenerator, $wrongPassword);
    }

    public function testLoginNotVerified(): void
    {
        $password = 'p@ssW0rd';
        $user = UserStub::random($password);
        $jwtGenerator = $this->createMock(JwtGeneratorInterface::class);
        $this->expectException(UserNotVerifiedException::class);
        $this->expectExceptionMessage("User is not verified");
        $user->login($jwtGenerator, $password);
    }
}