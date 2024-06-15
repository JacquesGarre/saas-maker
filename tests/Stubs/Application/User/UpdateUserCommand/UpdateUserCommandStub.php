<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Application\User\UpdateUserCommand;

use App\Application\User\UpdateUserCommand\UpdateUserCommand;
use Faker\Factory;

final class UpdateUserCommandStub
{
    public static function random(): UpdateUserCommand
    {
        $faker = Factory::create();
        return new UpdateUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->name(),
            $faker->email(),
            'p@ssW0rd'
        );
    }
}