<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\Auth\GetJwtQuery;

use App\Application\Auth\GetJwtQuery\GetJwtQuery;
use App\Application\Auth\LoginCommand\LoginCommand;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\Shared\QueryBusInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Tests\Stubs\Domain\User\UserStub;
use App\Domain\Shared\CommandBusInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class GetJwtQueryHandlerTest extends KernelTestCase {

    private readonly CommandBusInterface $commandBus;
    private readonly QueryBusInterface $queryBus;
    private readonly UserRepositoryInterface $repository;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->commandBus = $container->get(CommandBusInterface::class);
        $this->queryBus = $container->get(QueryBusInterface::class);
        $this->repository = $container->get(UserRepositoryInterface::class);
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
        $this->commandBus->dispatch($command);
        $fetchedUser = $this->repository->ofId($user->id());
        self::assertNotNull($fetchedUser->jwt());

        $query = new GetJwtQuery($user->email()->value);
        $queryResult = $this->queryBus->dispatch($query);
        self::assertEquals($fetchedUser->jwt()->value, $queryResult->jwt);
    }

    public function testUserNotFoundException(): void 
    {   
        $email = Factory::create()->email();
        $query = new GetJwtQuery($email);
        $this->expectException(UserNotFoundException::class);
        $this->queryBus->dispatch($query);
    }
}
