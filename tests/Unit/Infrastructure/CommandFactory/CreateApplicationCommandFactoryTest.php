<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\CommandFactory;

use App\Application\Application\CreateApplicationCommand\CreateApplicationCommand;
use App\Infrastructure\CommandFactory\CreateApplicationCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class CreateApplicationCommandFactoryTest extends TestCase
{

    public function testFromRequestWithValidData(): void
    {
        $faker = Factory::create();
        $requestData = [
            'id' => $faker->uuid(),
            'name' => $faker->name(),
            'subdomain' => $faker->slug()
        ];
        $request = new Request([], [], [], [], [], [], json_encode($requestData));
        $user = UserStub::random();
        $command = CreateApplicationCommandFactory::fromRequestAndUser($request, $user);
        $this->assertInstanceOf(CreateApplicationCommand::class, $command);
        $this->assertSame($requestData['id'], $command->id);
        $this->assertSame($requestData['name'], $command->name);
        $this->assertSame($requestData['subdomain'], $command->subdomain);
        $this->assertSame($user->id()->value->toString(), $command->createdById);
    }

    public function testFromRequestWithInvalidData(): void
    {
        $invalidJson = '{"id": "123", "firs'; 
        $request = new Request([], [], [], [], [], [], $invalidJson);
        $this->expectException(InvalidRequestContentException::class);
        $this->expectExceptionMessage("Invalid json body");
        $user = UserStub::random();
        CreateApplicationCommandFactory::fromRequestAndUser($request, $user);
    }
}