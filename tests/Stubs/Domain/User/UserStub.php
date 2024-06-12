<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\User;

use Faker\Factory;
use App\Domain\User\Email;
use App\Domain\User\FirstName;
use App\Domain\User\LastName;
use App\Domain\User\User;
use App\Tests\Stubs\Domain\Shared\IdStub;

final class UserStub
{
    public static function random(): User
    {
        $faker = Factory::create();
        $id = IdStub::random();
        $email = Email::fromString($faker->email());
        $firstName = new FirstName($faker->name());
        $lastName = new LastName($faker->name());
        $passwordHash = PasswordHashStub::random();
        return User::create(
            $id,
            $firstName,
            $lastName,
            $email,
            $passwordHash
        );
    }
}