<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\CommandFactory;

use App\Application\Application\CreateApplicationUserCommand\CreateApplicationUserCommand;
use App\Infrastructure\CommandFactory\CreateApplicationUserCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class CreateApplicationUserCommandFactoryTest extends TestCase
{

    public function testFromRequestWithValidData(): void
    {
        $faker = Factory::create();
        $requestData = ['email' => $faker->email()];
        $request = new Request([], [], ['uuid'=>$faker->uuid()], [], [], [], json_encode($requestData));
        $user = UserStub::random();
        $command = CreateApplicationUserCommandFactory::fromRequestAndUser($request, $user);
        $this->assertInstanceOf(CreateApplicationUserCommand::class, $command);
        $this->assertSame($request->attributes->get('uuid'), $command->applicationId);
        $this->assertSame($requestData['email'], $command->email);
        $this->assertSame($user->id()->value->toString(), $command->invitedById);
    }

    public function testFromRequestWithInvalidData(): void
    {
        $invalidJson = '{"id": "123", "firs'; 
        $request = new Request([], [], [], [], [], [], $invalidJson);
        $this->expectException(InvalidRequestContentException::class);
        $this->expectExceptionMessage("Invalid json body");
        $user = UserStub::random();
        CreateApplicationUserCommandFactory::fromRequestAndUser($request, $user);
    }
}