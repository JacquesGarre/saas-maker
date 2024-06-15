<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\UpdateUserCommand;

use App\Application\User\UpdateUserCommand\UpdateUserCommand;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class UpdateUserCommandTest extends TestCase {

    public function testConstructor(): void
    {
        $faker = Factory::create();
        $id = $faker->uuid();
        $firstName = $faker->name();
        $lastName = $faker->name();
        $email = $faker->email();
        $password = $faker->password();
        $command = new UpdateUserCommand(
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
        $command = new UpdateUserCommand();
        self::assertNull($command->id);
        self::assertNull($command->firstName);
        self::assertNull($command->lastName);
        self::assertNull($command->email);
        self::assertNull($command->password);
    }
}