<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\User\VerifyUserCommand;

use App\Application\User\VerifyUserCommand\VerifyUserCommand;
use App\Application\User\VerifyUserCommand\VerifyUserCommandHandler;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\Shared\Id;
use App\Domain\User\UserRepositoryInterface;
use App\Tests\Stubs\Domain\User\UserStub;
use App\Domain\Shared\EventBusInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class VerifyUserCommandHandlerTest extends KernelTestCase
{
    private readonly UserRepositoryInterface $repository;
    private $eventBus;
    private readonly VerifyUserCommandHandler $handler;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->repository = $container->get(UserRepositoryInterface::class);
        $this->eventBus = $this->createMock(EventBusInterface::class);
        $this->handler = new VerifyUserCommandHandler($this->repository, $this->eventBus);
    }

    public function testSunnyCase(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);
        self::assertFalse($user->isVerified()->value);
        $command = new VerifyUserCommand(
            $user->id()->value->toString()
        );
        $this->eventBus->expects($this->once())->method('notifyAll');
        ($this->handler)($command);
        $updatedUser = $this->repository->ofId(new Id($command->id));
        self::assertTrue($updatedUser->isVerified()->value);
    }

    public function testUserNotFoundException(): void
    {
        $user = UserStub::random();
        $command = new VerifyUserCommand(
            $user->id()->value->toString()
        );
        $this->expectException(UserNotFoundException::class);
        ($this->handler)($command);
    }
}