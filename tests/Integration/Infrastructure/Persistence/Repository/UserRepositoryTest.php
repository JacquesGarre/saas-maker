<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Persistence\Repository;

use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\Persistence\Repository\UserRepository;
use App\Tests\Stubs\Domain\User\UserStub;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use PDO;
use PDOException;

final class UserRepositoryTest extends KernelTestCase
{
    private UserRepositoryInterface $repository;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->repository = $container->get(UserRepositoryInterface::class);
    }

    public function testAdd(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);

        $fetchedUser = $this->repository->ofId($user->id);
        self::assertEquals($user, $fetchedUser);

        $this->repository->remove($user);
        $fetchedUser = $this->repository->ofId($user->id);
        self::assertNull($fetchedUser);
    }
}