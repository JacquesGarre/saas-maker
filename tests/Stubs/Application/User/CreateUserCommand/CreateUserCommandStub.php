<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Application\User\CreateUserCommand;

use App\Application\User\CreateUserCommand\CreateUserCommand;
use Faker\Factory;

final class CreateUserCommandStub
{
    public static function random(): CreateUserCommand
    {
        $faker = Factory::create();
        return new CreateUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->name(),
            $faker->email(),
            'p@ssW0rd'
        );
    }
}