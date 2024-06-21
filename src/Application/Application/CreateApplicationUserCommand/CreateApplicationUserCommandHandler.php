<?php

declare(strict_types=1);

namespace App\Application\Application\CreateApplicationUserCommand;

use App\Domain\Shared\Id;
use App\Domain\Shared\EventBusInterface;
use App\Domain\Application\ApplicationRepositoryInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\Application\Exception\ApplicationAlreadyCreatedException;
use App\Domain\Application\Exception\ApplicationNotFoundException;
use App\Domain\Shared\EmailAddress;
use App\Domain\User\Exception\PermissionNotAllowedException;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\User;

final class CreateApplicationUserCommandHandler {
    
    public function __construct(
        private readonly ApplicationRepositoryInterface $applicationRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventBusInterface $eventBus
    ) {
        
    }

    // TODO : TEST THIS
    /**
     * @throws ApplicationAlreadyCreatedException
     */
    public function __invoke(CreateApplicationUserCommand $command): void
    {
        $applicationId = new Id($command->applicationId);
        $application = $this->applicationRepository->ofId($applicationId);
        if (!$application) {
            throw new ApplicationNotFoundException("Application not found");
        }
        $invitedBy = $this->userRepository->ofId(new Id($command->invitedById));
        if (!$invitedBy) {
            throw new UserNotFoundException("User not found");
        }
        if (!$application->users()->hasUser($invitedBy)) { // TODO : Move in the domain, in addUser method when roles
            throw new PermissionNotAllowedException("Action not permitted");
        }

        $email = new EmailAddress($command->email); 
        $user = $this->userRepository->findOneByEmail($email);
        if (!$user) {
            $user = User::fromEmail($email);
        }
        $application->addUser($user);
        $this->eventBus->notifyAll($application->domainEvents, $user->domainEvents);
    }
}