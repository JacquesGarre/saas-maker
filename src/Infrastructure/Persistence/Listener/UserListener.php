<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Listener;

use Doctrine\ORM\Event\PostLoadEventArgs;
use App\Domain\User\User;
use Doctrine\ORM\EntityManagerInterface;

final class UserListener
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function postLoad(PostLoadEventArgs $args): void
    {
        $user = $args->getObject();
        if ($user instanceof User) {
            $user->initDomainEventCollection();
        }
    }
}