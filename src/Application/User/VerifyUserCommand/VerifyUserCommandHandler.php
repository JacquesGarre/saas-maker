<?php

declare(strict_types=1);

namespace App\Application\User\VerifyUserCommand;

use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\Shared\Id;
use App\Domain\Shared\EventBusInterface;
use App\Domain\User\UserRepositoryInterface;

final class VerifyUserCommandHandler {
    
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly EventBusInterface $eventBus
    ) {
    }

    public function __invoke(VerifyUserCommand $command): void
    {
        $id = new Id($command->id); 
        $user = $this->repository->ofId($id);
        if (!$user) {
            throw new UserNotFoundException("User to verify not found");
        }
        $user->verify();
        $this->eventBus->notifyAll($user->domainEvents);
    }
}