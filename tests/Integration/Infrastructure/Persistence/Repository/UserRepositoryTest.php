<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Persistence\Repository;

use App\Domain\Shared\EmailAddress;
use App\Domain\Shared\TokenGeneratorInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\VerificationToken;
use App\Tests\Stubs\Domain\Shared\IdStub;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class UserRepositoryTest extends KernelTestCase
{
    private UserRepositoryInterface $repository;
    private TokenGeneratorInterface $tokenGenerator;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->repository = $container->get(UserRepositoryInterface::class);
        $this->tokenGenerator = $container->get(TokenGeneratorInterface::class);
    }

    public function testAdd(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);

        $fetchedUser = $this->repository->ofId($user->id());
        self::assertEquals($user, $fetchedUser);
    }

    public function testRemove(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);

        $fetchedUser = $this->repository->ofId($user->id());
        self::assertEquals($user, $fetchedUser);

        $this->repository->remove($user);
        $fetchedUser = $this->repository->ofId($user->id());
        self::assertNull($fetchedUser);
    }

    public function testFindOneByEmailOrId(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);

        $fetchedUser = $this->repository->findOneByEmailOrId($user->email(), $user->id());
        self::assertEquals($user, $fetchedUser);

        $randomId = IdStub::random();
        $fetchedUser = $this->repository->findOneByEmailOrId($user->email(), $randomId);
        self::assertEquals($user, $fetchedUser);

        $randomEmail = EmailAddress::fromString(Factory::create()->email());
        $fetchedUser = $this->repository->findOneByEmailOrId($randomEmail, $user->id());
        self::assertEquals($user, $fetchedUser);
    }

    public function testFindOneByEmail(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);

        $fetchedUser = $this->repository->findOneByEmail($user->email());
        self::assertEquals($user, $fetchedUser);

        $randomEmail = EmailAddress::fromString(Factory::create()->email());
        $fetchedUser = $this->repository->findOneByEmail($randomEmail);
        self::assertNull($fetchedUser);
    }

    public function testFindOneByVerificationToken(): void
    {
        $user = UserStub::random();
        $user->generateVerificationToken($this->tokenGenerator);
        $this->repository->add($user);

        $fetchedUser = $this->repository->findOneByVerificationToken($user->verificationToken());
        self::assertEquals($user, $fetchedUser);

        $randomToken = VerificationToken::fromString(Factory::create()->text());
        $fetchedUser = $this->repository->findOneByVerificationToken($randomToken);
        self::assertNull($fetchedUser);
    }
}