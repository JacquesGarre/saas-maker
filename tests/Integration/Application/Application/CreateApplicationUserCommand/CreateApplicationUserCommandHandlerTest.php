<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\Application\CreateApplicationUserCommand;

use App\Application\Application\CreateApplicationUserCommand\CreateApplicationUserCommand;
use App\Application\Application\CreateApplicationUserCommand\CreateApplicationUserCommandHandler;
use App\Domain\Application\ApplicationRepositoryInterface;
use App\Domain\Application\Exception\ApplicationNotFoundException;
use App\Domain\Shared\CommandBusInterface;
use App\Domain\Shared\EmailAddress;
use App\Domain\Shared\EventBusInterface;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\UserRepositoryInterface;
use App\Tests\Stubs\Domain\Application\ApplicationStub;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory as Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class CreateApplicationUserCommandHandlerTest extends KernelTestCase {

    private readonly CommandBusInterface $commandBus;
    private readonly UserRepositoryInterface $userRepository;
    private readonly ApplicationRepositoryInterface $applicationRepository;
    private $eventBus;
    private readonly CreateApplicationUserCommandHandler $handler;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->commandBus = $container->get(CommandBusInterface::class);
        $this->userRepository = $container->get(UserRepositoryInterface::class);
        $this->applicationRepository = $container->get(ApplicationRepositoryInterface::class);
        $this->eventBus = $this->createMock(EventBusInterface::class);
        $this->handler = new CreateApplicationUserCommandHandler(
            $this->applicationRepository,
            $this->userRepository, 
            $this->eventBus
        );
    }

    public function testSunnyCase(): void
    {
        $faker = Factory::create();

        $inviter = UserStub::random();
        $this->userRepository->add($inviter);

        $application = ApplicationStub::random($inviter);
        $this->applicationRepository->add($application);

        $email = $faker->email();

        $command = new CreateApplicationUserCommand(
            $application->id->value->toString(),
            $email,
            $inviter->id()->value->toString()
        );
        $this->eventBus->expects($this->once())->method('notifyAll');
        ($this->handler)($command);

        $this->applicationRepository->testReset();
        $application = $this->applicationRepository->ofId($application->id);
        self::assertNotNull($application);

        $this->userRepository->testReset();
        $fetchedUser = $this->userRepository->findOneByEmail(EmailAddress::fromString($email));
        self::assertNotNull($fetchedUser);
        self::assertTrue($application->hasUser($fetchedUser));
    }

    public function testApplicationNotFoundException(): void
    {
        $faker = Factory::create();
        $inviter = UserStub::random();
        $this->userRepository->add($inviter);
        $application = ApplicationStub::random($inviter);
        $email = $faker->email();
        $command = new CreateApplicationUserCommand(
            $application->id->value->toString(),
            $email,
            $inviter->id()->value->toString()
        );
        $this->expectException(ApplicationNotFoundException::class);
        ($this->handler)($command);
    }

    public function testUserNotFoundException(): void
    {
        $faker = Factory::create();
        $user = UserStub::random();
        $this->userRepository->add($user);
        $application = ApplicationStub::random($user);
        $this->applicationRepository->add($application);
        $inviter = UserStub::random();
        $email = $faker->email();
        $command = new CreateApplicationUserCommand(
            $application->id->value->toString(),
            $email,
            $inviter->id()->value->toString()
        );
        $this->expectException(UserNotFoundException::class);
        ($this->handler)($command);
    }

    public function testInviteExistingUser(): void
    {
 
        $inviter = UserStub::random();
        $this->userRepository->add($inviter);
        $application = ApplicationStub::random($inviter);
        $this->applicationRepository->add($application);
        $user = UserStub::random();
        $this->userRepository->add($user);
        $command = new CreateApplicationUserCommand(
            $application->id->value->toString(),
            $user->email()->value,
            $inviter->id()->value->toString()
        );
        $this->eventBus->expects($this->once())->method('notifyAll');
        ($this->handler)($command);

        $this->applicationRepository->testReset();
        $application = $this->applicationRepository->ofId($application->id);
        self::assertNotNull($application);

        $this->userRepository->testReset();
        self::assertTrue($application->hasUser($user));
    }
}