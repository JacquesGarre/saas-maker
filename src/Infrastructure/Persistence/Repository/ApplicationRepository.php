<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Shared\Id;
use App\Domain\Application\Application;
use App\Domain\Application\Subdomain;
use App\Domain\Application\ApplicationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class ApplicationRepository implements ApplicationRepositoryInterface {
    
    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(Application::class);
    }

    public function ofId(Id $id): ?Application
    {   
        return $this->repository->find($id->value);
    }

    public function add(Application $application): void
    {
        dd($application);
        $this->entityManager->persist($application);
        $this->entityManager->flush();
    }

    public function remove(Application $application): void
    {
        $this->entityManager->remove($application);
        $this->entityManager->flush();
    }

    public function findOneBySubdomain(Subdomain $subdomain): ?Application
    {
        $qb = $this->entityManager->createQueryBuilder();
        $condition = $qb->expr()->eq('u.subdomain.value', ':subdomain');
        $qb->select('u')
            ->from(Application::class, 'u')
            ->andWhere($condition)
            ->setMaxResults(1)
            ->setParameter('subdomain', $subdomain->value);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function testReset(): void
    {
        $this->entityManager->clear();
    }
}