<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\CommandFactory;

use App\Application\Auth\LoginCommand\LoginCommand;
use App\Infrastructure\CommandFactory\LoginCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class LoginCommandFactoryTest extends TestCase
{

    public function testFromRequestWithValidData(): void
    {
        $faker = Factory::create();
        $requestData = [
            'email' => $faker->email(),
            'password' => $faker->password()
        ];
        $request = new Request([], [], [], [], [], [], json_encode($requestData));
        $command = LoginCommandFactory::fromRequest($request);
        $this->assertInstanceOf(LoginCommand::class, $command);
        $this->assertSame($requestData['email'], $command->email);
        $this->assertSame($requestData['password'], $command->password);
    }

    public function testFromRequestWithInvalidData(): void
    {
        $invalidJson = '{"email": "blablabla@blabla.com", "pass'; 
        $request = new Request([], [], [], [], [], [], $invalidJson);
        $this->expectException(InvalidRequestContentException::class);
        $this->expectExceptionMessage("Invalid json body");
        LoginCommandFactory::fromRequest($request);
    }
}