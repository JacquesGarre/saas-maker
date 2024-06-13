<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\Id;
use App\Domain\Shared\UpdatedAt;
use App\Domain\User\FirstName;
use App\Domain\User\LastName;
use App\Domain\User\Email;
use App\Domain\User\User;
use App\Domain\User\IsVerified;
use App\Domain\User\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class UserRepository implements UserRepositoryInterface {

    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function ofId(Id $id): User
    {
        return $this->repository->findOneBy(['id' => $id->value->toString()]);
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

    public function findBy( // TODO: Refactor with Criteria pattern
        ?FirstName $firstName = null, 
        ?LastName $lastName = null, 
        ?Email $email = null,
        ?IsVerified $isVerified = null,
        ?CreatedAt $createdBefore = null,
        ?CreatedAt $createdAfter = null,
        ?UpdatedAt $updatedBefore = null,
        ?UpdatedAt $updatedAfter = null,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $criteria = [];
        if ($firstName) {
            $criteria['firstName'] = $firstName->value;
        }
        if ($lastName) {
            $criteria['lastName'] = $lastName->value;
        }
        if ($email) {
            $criteria['email'] = $email->value;
        }
        if ($isVerified) {
            $criteria['isVerified'] = $isVerified->value;
        }
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }
}