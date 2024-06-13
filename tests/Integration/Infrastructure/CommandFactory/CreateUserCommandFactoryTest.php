<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\CommandFactory;

use App\Application\User\CreateUserCommand\CreateUserCommand;
use App\Infrastructure\CommandFactory\CreateUserCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class CreateUserCommandFactoryTest extends WebTestCase
{

    private CreateUserCommandFactory $factory;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->factory = $container->get(CreateUserCommandFactory::class);
    }

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
        $command = $this->factory->fromRequest($request);
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
        $this->factory->fromRequest($request);
    }

    public function testFromRequestWithMissingFields(): void
    {
        $faker = Factory::create();
        $requestData = [
            'id' => $faker->uuid(),
            'first_name' => $faker->name()
        ];
        $request = new Request([], [], [], [], [], [], json_encode($requestData));
        $this->expectException(ValidationFailedException::class);
        $this->factory->fromRequest($request);
    }
}