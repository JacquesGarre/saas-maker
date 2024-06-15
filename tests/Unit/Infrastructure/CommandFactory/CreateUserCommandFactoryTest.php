<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\CommandFactory;

use App\Application\User\CreateUserCommand\CreateUserCommand;
use App\Infrastructure\CommandFactory\CreateUserCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class CreateUserCommandFactoryTest extends TestCase
{

    public function testFromRequestWithValidData(): void
    {
        $faker = Factory::create();
        $requestData = [
            'id' => $faker->uuid(),
            'first_name' => $faker->name(),
            'last_name' => $faker->name(),
            'email' => $faker->email(),
            'password' => $faker->password()
        ];
        $request = new Request([], [], [], [], [], [], json_encode($requestData));
        $command = CreateUserCommandFactory::fromRequest($request);
        $this->assertInstanceOf(CreateUserCommand::class, $command);
        $this->assertSame($requestData['id'], $command->id);
        $this->assertSame($requestData['first_name'], $command->firstName);
        $this->assertSame($requestData['last_name'], $command->lastName);
        $this->assertSame($requestData['email'], $command->email);
        $this->assertSame($requestData['password'], $command->password);
    }

    public function testFromRequestWithInvalidData(): void
    {
        $invalidJson = '{"id": "123", "firs'; 
        $request = new Request([], [], [], [], [], [], $invalidJson);
        $this->expectException(InvalidRequestContentException::class);
        $this->expectExceptionMessage("Invalid json body");
        CreateUserCommandFactory::fromRequest($request);
    }
}