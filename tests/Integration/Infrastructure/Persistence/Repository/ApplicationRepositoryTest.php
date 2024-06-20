<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Persistence\Repository;

use App\Domain\Application\Application;
use App\Domain\Application\ApplicationRepositoryInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Tests\Stubs\Domain\Application\ApplicationStub;
use App\Tests\Stubs\Domain\User\UserStub;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ApplicationRepositoryTest extends KernelTestCase
{
    private ApplicationRepositoryInterface $repository;
    private UserRepositoryInterface $userRepository;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->repository = $container->get(ApplicationRepositoryInterface::class);
        $this->userRepository = $container->get(UserRepositoryInterface::class);
    }

    public function testAdd(): void
    {   
        $user = UserStub::random();
        $this->userRepository->add($user);

        $application = ApplicationStub::random($user);
        $this->repository->add($application);

        $this->repository->testReset();
        $fetchedApplication = $this->repository->ofId($application->id);

        self::assertApplicationEquals($application, $fetchedApplication);
    }

    private static function assertApplicationEquals(Application $application, Application $fetchedApplication): void
    {
        self::assertTrue($application->id->equals($fetchedApplication->id));
        self::assertEquals($application->name->value, $fetchedApplication->name->value);
        self::assertEquals($application->subdomain->value, $fetchedApplication->subdomain->value);
        self::assertTrue($application->createdBy->id()->equals($fetchedApplication->createdBy->id()));
        self::assertEquals($application->createdBy->firstName()->value, $fetchedApplication->createdBy->firstName()->value);
        self::assertEquals($application->createdBy->lastName()->value, $fetchedApplication->createdBy->lastName()->value);
        self::assertEquals($application->createdBy->email()->value, $fetchedApplication->createdBy->email()->value);
        self::assertEquals($application->createdBy->createdAt()->value->getTimestamp(), $fetchedApplication->createdBy->createdAt()->value->getTimestamp());
        self::assertEquals($application->createdBy->updatedAt()->value->getTimestamp(), $fetchedApplication->createdBy->updatedAt()->value->getTimestamp());
        self::assertCount(1, $fetchedApplication->users());
        self::assertTrue($application->createdBy->id()->equals($fetchedApplication->users()->first()->user->id()));
    }

    public function testRemove(): void
    {
        $user = UserStub::random();
        $this->userRepository->add($user);

        $application = ApplicationStub::random($user);
        $this->repository->add($application);

        $fetchedApplication = $this->repository->ofId($application->id);
        self::assertApplicationEquals($application, $fetchedApplication);

        $this->repository->remove($application);
        $this->repository->testReset();

        $fetchedApplication = $this->repository->ofId($application->id);
        self::assertNull($fetchedApplication);
    }

    public function testFindOneBySubdomain(): void
    {
        $user = UserStub::random();
        $this->userRepository->add($user);

        $application = ApplicationStub::random($user);
        $this->repository->add($application);
        $this->repository->testReset();

        $fetchedApplication = $this->repository->findOneBySubdomain($application->subdomain);
        self::assertApplicationEquals($application, $fetchedApplication);
    }
}