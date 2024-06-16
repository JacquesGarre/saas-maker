<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Persistence\Repository;

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

        $fetchedApplication = $this->repository->ofId($application->id);
        self::assertEquals($application, $fetchedApplication);
    }

    public function testRemove(): void
    {
        $user = UserStub::random();
        $this->userRepository->add($user);

        $application = ApplicationStub::random($user);
        $this->repository->add($application);

        $fetchedApplication = $this->repository->ofId($application->id);
        self::assertEquals($application, $fetchedApplication);

        $this->repository->remove($application);
        $fetchedApplication = $this->repository->ofId($application->id);
        self::assertNull($fetchedApplication);
    }

    public function testFindOneBySubdomain(): void
    {
        $user = UserStub::random();
        $this->userRepository->add($user);

        $application = ApplicationStub::random($user);
        $this->repository->add($application);

        $fetchedApplication = $this->repository->findOneBySubdomain($application->subdomain);
        self::assertEquals($application, $fetchedApplication);
    }
}