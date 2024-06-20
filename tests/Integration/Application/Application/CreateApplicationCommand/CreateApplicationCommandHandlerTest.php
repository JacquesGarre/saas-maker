<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\Application\CreateApplicationCommand;

use App\Application\Application\CreateApplicationCommand\CreateApplicationCommand;
use App\Application\Application\CreateApplicationCommand\CreateApplicationCommandHandler;
use App\Application\User\Exception\UserNotFoundException;
use App\Domain\Application\ApplicationRepositoryInterface;
use App\Domain\Application\Exception\ApplicationAlreadyCreatedException;
use App\Domain\Shared\CommandBusInterface;
use App\Domain\Shared\EventBusInterface;
use App\Domain\Shared\Id;
use App\Domain\User\UserRepositoryInterface;
use App\Tests\Stubs\Domain\Application\ApplicationStub;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory as Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class CreateApplicationCommandHandlerTest extends KernelTestCase {

    private readonly CommandBusInterface $commandBus;
    private readonly UserRepositoryInterface $userRepository;
    private readonly ApplicationRepositoryInterface $applicationRepository;
    private $eventBus;
    private readonly CreateApplicationCommandHandler $handler;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->commandBus = $container->get(CommandBusInterface::class);
        $this->userRepository = $container->get(UserRepositoryInterface::class);
        $this->applicationRepository = $container->get(ApplicationRepositoryInterface::class);
        $this->eventBus = $this->createMock(EventBusInterface::class);
        $this->handler = new CreateApplicationCommandHandler(
            $this->applicationRepository,
            $this->userRepository, 
            $this->eventBus
        );
    }

    public function testSunnyCase(): void
    {
        $faker = Factory::create();
        $user = UserStub::random();
        $this->userRepository->add($user);
        $this->userRepository->testReset();
        $command = new CreateApplicationCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->slug(),
            $user->id()->value->toString()
        );
        $this->eventBus->expects($this->once())->method('notifyAll');
        ($this->handler)($command);

        $this->applicationRepository->testReset();
        $application = $this->applicationRepository->ofId(new Id($command->id));
        self::assertNotNull($application);
        self::assertEquals($command->id, $application->id->value->toString());
        self::assertEquals($command->name, $application->name->value);
        self::assertEquals($command->subdomain, $application->subdomain->value);
        self::assertEquals($command->createdById, $application->createdBy->id()->value->toString());
    }

    public function testApplicationAlreadyCreatedWithIdException(): void
    {
        $faker = Factory::create();
        $user = UserStub::random();
        $this->userRepository->add($user);
        $application = ApplicationStub::random($user);
        $this->applicationRepository->add($application);

        $command = new CreateApplicationCommand(
            $application->id->value->toString(),
            $faker->name(),
            $faker->slug(),
            $user->id()->value->toString()
        );
        $this->expectException(ApplicationAlreadyCreatedException::class);
        $this->expectExceptionMessage("Application with same id already exists");
        $this->commandBus->dispatch($command);
    }

    public function testApplicationAlreadyCreatedWithSubdomainException(): void
    {
        $faker = Factory::create();
        $user = UserStub::random();
        $this->userRepository->add($user);
        $application = ApplicationStub::random($user);
        $this->applicationRepository->add($application);

        $command = new CreateApplicationCommand(
            $faker->uuid(),
            $faker->name(),
            $application->subdomain->value,
            $user->id()->value->toString()
        );
        $this->expectException(ApplicationAlreadyCreatedException::class);
        $this->expectExceptionMessage("Application with same subdomain already exists");
        $this->commandBus->dispatch($command);
    }

    public function testUserNotFoundException(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->slug(),
            $faker->uuid()
        );
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage("User not found");
        $this->commandBus->dispatch($command);
    }
}