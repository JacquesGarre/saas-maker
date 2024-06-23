<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Listener;

use Doctrine\ORM\Event\PostLoadEventArgs;
use App\Domain\Application\Application;
use App\Domain\Application\ApplicationUser;
use App\Domain\Application\ApplicationUserCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;

final class ApplicationListener
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function postLoad(PostLoadEventArgs $args): void
    {
        $application = $args->getObject();
        if ($application instanceof Application) {
            $application->initDomainEventCollection();
            $applicationUserCollection = new ApplicationUserCollection();
            $users = $this->em->getRepository(ApplicationUser::class)->findBy(['application' => $application]);
            foreach ($users as $user) {
                $applicationUserCollection->add($user);
            }
            $application->setUsers($applicationUserCollection);
        }
    }

    public function preRemove(PreRemoveEventArgs $args): void
    {
        $application = $args->getObject();
        if ($application instanceof Application) {
            foreach($application->users() as $applicationUser) {
                $this->em->remove($applicationUser);
            }
        }
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $application = $args->getObject();
        if ($application instanceof Application) {
            foreach($application->users() as $applicationUser) {
                $this->em->persist($applicationUser);
            }
        }
    }
}