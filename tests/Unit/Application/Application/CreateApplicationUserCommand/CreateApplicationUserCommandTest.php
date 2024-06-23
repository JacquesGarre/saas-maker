<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Application\CreateApplicationUserCommand;

use App\Application\Application\CreateApplicationUserCommand\CreateApplicationUserCommand;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class CreateApplicationUserCommandTest extends TestCase {

    public function testConstructor(): void
    {
        $faker = Factory::create();
        $applicationId = $faker->uuid();
        $email = $faker->email();
        $invitedById = $faker->uuid();
        $command = new CreateApplicationUserCommand(
            $applicationId,
            $email,
            $invitedById
        );
        self::assertEquals($applicationId, $command->applicationId);
        self::assertEquals($email, $command->email);
        self::assertEquals($invitedById, $command->invitedById);
    }

    public function testConstructorWithNulls(): void
    {
        $command = new CreateApplicationUserCommand();
        self::assertNull($command->applicationId);
        self::assertNull($command->email);
        self::assertNull($command->invitedById);
    }
}