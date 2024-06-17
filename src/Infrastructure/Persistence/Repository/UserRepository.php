<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Shared\Id;
use App\Domain\Shared\EmailAddress;
use App\Domain\User\User;
use App\Domain\Auth\Jwt;
use App\Domain\User\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class UserRepository implements UserRepositoryInterface {

    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function ofId(Id $id): ?User
    {
        return $this->repository->find($id->value);
    }

    public function add(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function remove(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function findOneByEmailOrId(EmailAddress $email, Id $id): ?User
    {
        $qb = $this->entityManager->createQueryBuilder();
        $condition = $qb->expr()->orX(
            $qb->expr()->eq('u.email.value', ':email'),
            $qb->expr()->eq('u.id.value', ':id')
        );
        $qb->select('u')
            ->from(User::class, 'u')
            ->andWhere($condition)
            ->setMaxResults(1)
            ->setParameter('email', $email->value)
            ->setParameter('id', $id->value->toString());
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findOneByEmail(EmailAddress $email): ?User
    {
        $qb = $this->entityManager->createQueryBuilder();
        $condition = $qb->expr()->eq('u.email.value', ':email');
        $qb->select('u')
            ->from(User::class, 'u')
            ->andWhere($condition)
            ->setMaxResults(1)
            ->setParameter('email', $email->value);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findOneByJwt(Jwt $jwt): ?User
    {
        $qb = $this->entityManager->createQueryBuilder();
        $condition = $qb->expr()->eq('u.jwt.value', ':jwt');
        $qb->select('u')
            ->from(User::class, 'u')
            ->andWhere($condition)
            ->setMaxResults(1)
            ->setParameter('jwt', $jwt->value);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function testReset(): void
    {
        $this->entityManager->clear();
    }
}