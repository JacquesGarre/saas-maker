<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\CommandFactory;

use App\Application\User\UpdateUserCommand\UpdateUserCommand;
use App\Infrastructure\CommandFactory\UpdateUserCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class UpdateUserCommandFactoryTest extends TestCase
{

    public function testFromRequestWithValidData(): void
    {
        $user = UserStub::random();
        $faker = Factory::create();
        $requestData = [
            'first_name' => $faker->name(),
            'last_name' => $faker->name(),
            'email' => $faker->email(),
            'password' => $faker->password()
        ];
        $request = new Request(
            [], 
            [], 
            ['uuid' => $user->id->value->toString()], 
            [], 
            [], 
            [], 
            json_encode($requestData)
        );
        $command = UpdateUserCommandFactory::fromRequestAndUser($request, $user);
        self::assertInstanceOf(UpdateUserCommand::class, $command);
        self::assertNotNull($command->id);
        self::assertEquals($request->attributes->get('uuid'), $command->id);
        self::assertEquals($requestData['first_name'], $command->firstName);
        self::assertEquals($requestData['last_name'], $command->lastName);
        self::assertEquals($requestData['email'], $command->email);
        self::assertEquals($requestData['password'], $command->password);
        self::assertEquals($user->id->value->toString(), $command->updatedById);
    }

    public function testFromRequestWithInvalidData(): void
    {
        $user = UserStub::random();
        $invalidJson = '{"id": "123", "firs'; 
        $request = new Request([], [], [], [], [], [], $invalidJson);
        $this->expectException(InvalidRequestContentException::class);
        $this->expectExceptionMessage("Invalid json body");
        UpdateUserCommandFactory::fromRequestAndUser($request, $user);
    }
}