<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\Auth\LoginCommand;

use App\Domain\Auth\Exception\InvalidCredentialsException;
use App\Application\Auth\LoginCommand\LoginCommand;
use App\Application\Auth\LoginCommand\LoginCommandHandler;
use App\Domain\Auth\JwtGeneratorInterface;
use App\Domain\Shared\CommandBusInterface;
use App\Domain\Shared\EventBusInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class LoginCommandHandlerTest extends KernelTestCase
{
    private readonly CommandBusInterface $commandBus;
    private readonly UserRepositoryInterface $repository;
    private $eventBus;
    private readonly LoginCommandHandler $handler;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->commandBus = $container->get(CommandBusInterface::class);
        $this->repository = $container->get(UserRepositoryInterface::class);
        $this->eventBus = $this->createMock(EventBusInterface::class);
        $jwtGenerator = $container->get(JwtGeneratorInterface::class);
        $this->handler = new LoginCommandHandler($this->repository, $this->eventBus, $jwtGenerator);
    }

    public function testSunnyCase(): void
    {   
        $password = 'p@ssw0Rd';
        $user = UserStub::randomVerified($password);
        $this->repository->add($user);
        $command = new LoginCommand(
            $user->email()->value,
            $password
        );
        $this->eventBus->expects($this->once())->method('notifyAll');
        ($this->handler)($command);
        $fetchedUser = $this->repository->ofId($user->id());
        self::assertNotNull($fetchedUser->jwt());
    }

    public function testInvalidCredentials(): void
    {   
        $faker = Factory::create();
        $command = new LoginCommand(
            $faker->email(),
            $faker->password()
        );
        $this->expectException(InvalidCredentialsException::class);
        $this->commandBus->dispatch($command);
    }
}