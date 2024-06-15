<?php

declare(strict_types=1);

namespace App\Application\User\UpdateUserCommand;

use App\Application\User\Exception\UserNotFoundException;
use App\Domain\Shared\Id;
use App\Domain\Shared\EventBusInterface;
use App\Domain\User\FirstName;
use App\Domain\User\LastName;
use App\Domain\User\Email;
use App\Domain\User\PasswordHash;
use App\Domain\User\UserRepositoryInterface;

final class UpdateUserCommandHandler {
    
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly EventBusInterface $eventBus
    ) {
    }

    public function __invoke(UpdateUserCommand $command): void
    {
        $id = new Id($command->id); 
        $user = $this->repository->ofId($id);
        if (!$user) {
            throw new UserNotFoundException("User to update not found");
        }

        $updatedById = new Id($command->updatedById);
        $updatedBy = $this->repository->ofId($updatedById);
        if (!$updatedBy) {
            throw new UserNotFoundException("Updating user not found");
        }

        $firstName = $command->firstName ? new FirstName($command->firstName) : null;
        $lastName = $command->lastName ? new LastName($command->lastName) : null;
        $email = $command->email ? Email::fromString($command->email) : null;
        $passwordHash = $command->password ? PasswordHash::fromPlainPassword($command->password) : null;
        $user->update(
            $updatedBy,
            $firstName,
            $lastName,
            $email,
            $passwordHash
        );
        $this->eventBus->notifyAll($user->domainEvents);
    }
}