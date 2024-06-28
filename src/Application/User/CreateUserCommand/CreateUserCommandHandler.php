<?php

declare(strict_types=1);

namespace App\Application\User\CreateUserCommand;

use App\Domain\User\Exception\UserAlreadyCreatedException;
use App\Domain\Shared\Id;
use App\Domain\Shared\EventBusInterface;
use App\Domain\User\FirstName;
use App\Domain\User\LastName;
use App\Domain\Shared\EmailAddress;
use App\Domain\User\PasswordHash;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;

final class CreateUserCommandHandler {
    
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly EventBusInterface $eventBus
    ) {
        
    }

    /**
     * @throws UserAlreadyCreatedException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $id = new Id($command->id); 
        $firstName = new FirstName($command->firstName);
        $lastName = new LastName($command->lastName);
        $email = EmailAddress::fromString($command->email);
        $passwordHash = PasswordHash::fromPlainPassword($command->password);
        if ($this->repository->findOneByEmailOrId($email, $id)) {
            throw new UserAlreadyCreatedException("An account with this email address has already been created");
        }
        $user = User::create(
            $id, 
            $firstName, 
            $lastName, 
            $email, 
            $passwordHash
        );
        $this->repository->add($user);
        $this->eventBus->notifyAll($user->domainEvents);
    }
}