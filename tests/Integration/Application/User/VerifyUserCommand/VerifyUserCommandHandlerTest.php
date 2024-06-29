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
use App\Domain\Shared\TokenGeneratorInterface;
use App\Domain\User\Exception\UserAlreadyVerifiedException;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class VerifyUserCommandHandlerTest extends KernelTestCase
{
    private readonly UserRepositoryInterface $repository;
    private $eventBus;
    private readonly VerifyUserCommandHandler $handler;
    private readonly TokenGeneratorInterface $tokenGenerator;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->repository = $container->get(UserRepositoryInterface::class);
        $this->eventBus = $this->createMock(EventBusInterface::class);
        $this->handler = new VerifyUserCommandHandler($this->repository, $this->eventBus);
        $this->tokenGenerator = $container->get(TokenGeneratorInterface::class);
    }

    public function testSunnyCase(): void
    {
        $user = UserStub::random();
        $user->generateVerificationToken($this->tokenGenerator);
        $this->repository->add($user);
        self::assertFalse($user->isVerified()->value);
        $command = new VerifyUserCommand($user->verificationToken()->value);
        $this->eventBus->expects($this->once())->method('notifyAll');
        ($this->handler)($command);
        $updatedUser = $this->repository->ofId($user->id());
        self::assertTrue($updatedUser->isVerified()->value);
    }

    public function testUserNotFoundException(): void
    {
        $faker = Factory::create();
        $command = new VerifyUserCommand($faker->text());
        $this->expectException(UserNotFoundException::class);
        ($this->handler)($command);
    }

    public function testUserAlreadyVerifiedException(): void
    {
        $user = UserStub::random();
        $user->generateVerificationToken($this->tokenGenerator);
        $user->verify();
        $this->repository->add($user);
        self::assertTrue($user->isVerified()->value);
        $command = new VerifyUserCommand($user->verificationToken()->value);
        $this->expectException(UserAlreadyVerifiedException::class);
        ($this->handler)($command);
    }
}