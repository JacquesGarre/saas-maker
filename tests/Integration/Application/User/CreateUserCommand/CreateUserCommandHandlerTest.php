<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\User\CreateUserCommand;

use App\Application\User\CreateUserCommand\CreateUserCommand;
use App\Application\User\CreateUserCommand\CreateUserCommandHandler;
use App\Application\User\Exception\UserAlreadyCreatedException;
use App\Domain\Shared\Id;
use App\Domain\User\PasswordHash;
use App\Domain\User\UserRepositoryInterface;
use App\Tests\Stubs\Application\User\CreateUserCommand\CreateUserCommandStub;
use App\Tests\Stubs\Domain\User\UserStub;
use DateTimeImmutable;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class CreateUserCommandHandlerTest extends KernelTestCase
{
    private readonly CreateUserCommandHandler $handler;
    private readonly UserRepositoryInterface $repository;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->handler = $container->get(CreateUserCommandHandler::class);
        $this->repository = $container->get(UserRepositoryInterface::class);
    }

    /**
     * @throws UserAlreadyCreatedException
     */
    public function testSunnyCase(): void
    {
        $command = CreateUserCommandStub::random();
        ($this->handler)($command);
        $user = $this->repository->ofId(new Id($command->id));
        self::assertNotNull($user);
        self::assertEquals($command->id, $user->id->value->toString());
        self::assertEquals($command->email, $user->email->value);
        self::assertEquals($command->firstName, $user->firstName->value);
        self::assertEquals($command->lastName, $user->lastName->value);

        $hash = PasswordHash::fromPlainPassword($command->password);
        self::assertTrue($hash->matches($command->password));

        self::assertEqualsWithDelta(
            (new DateTimeImmutable())->getTimestamp(),
            $user->createdAt->value->getTimestamp(),
            1
        );
        self::assertEqualsWithDelta(
            (new DateTimeImmutable())->getTimestamp(),
            $user->updatedAt->value->getTimestamp(),
            1
        );
        $this->repository->remove($user);
    }

    public function testUserAlreadyCreatedExceptionWithSameId(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);

        $command = new CreateUserCommand(
            $user->id->value->toString(),
            Factory::create()->name(),
            Factory::create()->name(),
            Factory::create()->email(),
            'p@ssw0Rd'
        );
        $this->expectException(UserAlreadyCreatedException::class);
        ($this->handler)($command);
        $this->repository->remove($user);
    }

    public function testUserAlreadyCreatedExceptionWithSameEmail(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);

        $command = new CreateUserCommand(
            Factory::create()->uuid(),
            Factory::create()->name(),
            Factory::create()->name(),
            $user->email->value,
            'p@ssw0Rd'
        );
        $this->expectException(UserAlreadyCreatedException::class);
        ($this->handler)($command);
        $this->repository->remove($user);
    }
}