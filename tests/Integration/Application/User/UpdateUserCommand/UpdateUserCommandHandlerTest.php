<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\User\UpdateUserCommand;

use App\Application\User\UpdateUserCommand\UpdateUserCommand;
use App\Application\User\UpdateUserCommand\UpdateUserCommandHandler;
use App\Application\User\Exception\UserNotFoundException;
use App\Domain\Shared\CommandBusInterface;
use App\Domain\Shared\Id;
use App\Domain\User\UserRepositoryInterface;
use App\Tests\Stubs\Application\User\UpdateUserCommand\UpdateUserCommandStub;
use App\Tests\Stubs\Domain\User\UserStub;
use App\Domain\Shared\EventBusInterface;
use DateTimeImmutable;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class UpdateUserCommandHandlerTest extends KernelTestCase
{
    private readonly UserRepositoryInterface $repository;
    private $eventBus;
    private readonly UpdateUserCommandHandler $handler;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->repository = $container->get(UserRepositoryInterface::class);
        $this->eventBus = $this->createMock(EventBusInterface::class);
        $this->handler = new UpdateUserCommandHandler($this->repository, $this->eventBus);
    }

    public function testSunnyCase(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);

        $command = new UpdateUserCommand(
            $user->id()->value->toString(),
            Factory::create()->firstName(),
            Factory::create()->lastName(),
            Factory::create()->email(),
            'p4ss@w0rD',
            $user->id()->value->toString()
        );
        $this->eventBus->expects($this->once())->method('notifyAll');
        ($this->handler)($command);

        $updatedUser = $this->repository->ofId(new Id($command->id));
        self::assertTrue($user->id()->equals($updatedUser->id()));
        self::assertEquals($command->id, $updatedUser->id()->value->toString());
        self::assertEquals($command->email, $updatedUser->email()->value);
        self::assertEquals($command->firstName, $updatedUser->firstName()->value);
        self::assertEquals($command->lastName, $updatedUser->lastName()->value);
        self::assertTrue($updatedUser->passwordHash()->matches($command->password));
        self::assertEquals($user->createdAt()->value->getTimestamp(), $updatedUser->createdAt()->value->getTimestamp());
        self::assertEqualsWithDelta(
            (new DateTimeImmutable())->getTimestamp(),
            $updatedUser->updatedAt()->value->getTimestamp(),
            1
        );
    }

    public function testWithNulls(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);

        $command = new UpdateUserCommand(
            $user->id()->value->toString(),
            null,
            null,
            null,
            null,
            $user->id()->value->toString()
        );
        $this->eventBus->expects($this->once())->method('notifyAll');
        ($this->handler)($command);

        $updatedUser = $this->repository->ofId(new Id($command->id));
        self::assertTrue($user->id()->equals($updatedUser->id()));
        self::assertEquals($command->id, $updatedUser->id()->value->toString());
        self::assertEquals($user->email()->value, $updatedUser->email()->value);
        self::assertEquals($user->firstName()->value, $updatedUser->firstName()->value);
        self::assertEquals($user->lastName()->value, $updatedUser->lastName()->value);
        self::assertEquals($user->passwordHash()->value, $updatedUser->passwordHash()->value);
        self::assertEquals($user->createdAt()->value->getTimestamp(), $updatedUser->createdAt()->value->getTimestamp());
        self::assertEqualsWithDelta(
            (new DateTimeImmutable())->getTimestamp(),
            $updatedUser->updatedAt()->value->getTimestamp(),
            1
        );
    }

    public function testUserNotFoundException(): void
    {
        $command = UpdateUserCommandStub::random();
        $this->expectException(UserNotFoundException::class);
        ($this->handler)($command);
    }
}