<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\User\CreateUserCommand;

use App\Application\User\CreateUserCommand\CreateUserCommand;
use App\Application\User\CreateUserCommand\CreateUserCommandHandler;
use App\Domain\Auth\JwtGeneratorInterface;
use App\Domain\User\Exception\UserAlreadyCreatedException;
use App\Domain\Shared\CommandBusInterface;
use App\Domain\Shared\Id;
use App\Domain\User\PasswordHash;
use App\Domain\User\UserRepositoryInterface;
use App\Tests\Stubs\Application\User\CreateUserCommand\CreateUserCommandStub;
use App\Tests\Stubs\Domain\User\UserStub;
use App\Domain\Shared\EventBusInterface;
use DateTimeImmutable;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class CreateUserCommandHandlerTest extends KernelTestCase
{
    private readonly CommandBusInterface $commandBus;
    private readonly UserRepositoryInterface $repository;
    private $eventBus;
    private readonly CreateUserCommandHandler $handler;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->commandBus = $container->get(CommandBusInterface::class);
        $this->repository = $container->get(UserRepositoryInterface::class);
        $this->eventBus = $this->createMock(EventBusInterface::class);
        $jwtGenerator = $container->get(JwtGeneratorInterface::class);
        $this->handler = new CreateUserCommandHandler($this->repository, $this->eventBus, $jwtGenerator);
    }

    public function testSunnyCase(): void
    {
        $command = CreateUserCommandStub::random();
        $this->eventBus->expects($this->once())->method('notifyAll');
        ($this->handler)($command);
        $user = $this->repository->ofId(new Id($command->id));
        self::assertNotNull($user);
        self::assertEquals($command->id, $user->id()->value->toString());
        self::assertEquals($command->email, $user->email()->value);
        self::assertEquals($command->firstName, $user->firstName()->value);
        self::assertEquals($command->lastName, $user->lastName()->value);

        $hash = PasswordHash::fromPlainPassword($command->password);
        self::assertTrue($hash->matches($command->password));

        self::assertEqualsWithDelta(
            (new DateTimeImmutable())->getTimestamp(),
            $user->createdAt()->value->getTimestamp(),
            1
        );
        self::assertEqualsWithDelta(
            (new DateTimeImmutable())->getTimestamp(),
            $user->updatedAt()->value->getTimestamp(),
            1
        );
    }

    public function testUserAlreadyCreatedExceptionWithSameId(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);

        $command = new CreateUserCommand(
            $user->id()->value->toString(),
            Factory::create()->name(),
            Factory::create()->name(),
            Factory::create()->email(),
            'p@ssw0Rd'
        );
        $this->expectException(UserAlreadyCreatedException::class);
        $this->commandBus->dispatch($command);
    }

    public function testUserAlreadyCreatedExceptionWithSameEmail(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);

        $command = new CreateUserCommand(
            Factory::create()->uuid(),
            Factory::create()->name(),
            Factory::create()->name(),
            $user->email()->value,
            'p@ssw0Rd'
        );
        $this->expectException(UserAlreadyCreatedException::class);
        $this->commandBus->dispatch($command);
    }
}