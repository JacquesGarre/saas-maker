<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\CreateUserCommand;

use App\Application\User\CreateUserCommand\CreateUserCommand;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class CreateUserCommandTest extends TestCase {

    public function testConstructor(): void
    {
        $faker = Factory::create();
        $id = $faker->uuid();
        $firstName = $faker->name();
        $lastName = $faker->name();
        $email = $faker->email();
        $password = $faker->password();
        $command = new CreateUserCommand(
            $id,
            $firstName,
            $lastName,
            $email,
            $password
        );
        self::assertEquals($id, $command->id);
        self::assertEquals($firstName, $command->firstName);
        self::assertEquals($lastName, $command->lastName);
        self::assertEquals($email, $command->email);
        self::assertEquals($password, $command->password);
    }

    public function testConstructorWithNulls(): void
    {
        $command = new CreateUserCommand();
        self::assertNull($command->id);
        self::assertNull($command->firstName);
        self::assertNull($command->lastName);
        self::assertNull($command->email);
        self::assertNull($command->password);
    }
}