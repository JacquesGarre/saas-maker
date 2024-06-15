<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Auth\LoginCommand;

use App\Application\Auth\LoginCommand\LoginCommand;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class LoginCommandTest extends TestCase {

    public function testConstructor(): void
    {
        $faker = Factory::create();
        $email = $faker->email();
        $password = $faker->password();
        $command = new LoginCommand(
            $email,
            $password
        );
        self::assertEquals($email, $command->email);
        self::assertEquals($password, $command->password);
    }

    public function testConstructorWithNulls(): void
    {
        $command = new LoginCommand();
        self::assertNull($command->email);
        self::assertNull($command->password);
    }
}