<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\VerifyUserCommand;

use App\Application\User\VerifyUserCommand\VerifyUserCommand;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class VerifyUserCommandTest extends TestCase {

    public function testConstructor(): void
    {
        $faker = Factory::create();
        $id = $faker->uuid();
        $command = new VerifyUserCommand($id);
        self::assertEquals($id, $command->id);
    }

    public function testConstructorWithNulls(): void
    {
        $command = new VerifyUserCommand();
        self::assertNull($command->id);
    }
}