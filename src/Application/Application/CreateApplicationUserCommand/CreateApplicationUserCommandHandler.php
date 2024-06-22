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
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\User;

final class CreateApplicationUserCommandHandler {
    
    public function __construct(
        private readonly ApplicationRepositoryInterface $applicationRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventBusInterface $eventBus
    ) {
        
    }

    // TODO : Test this
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
        $inviter = $this->userRepository->ofId(new Id($command->invitedById));
        if (!$inviter) {
            throw new UserNotFoundException("User not found");
        }
        $email = EmailAddress::fromString($command->email); 
        $user = $this->userRepository->findOneByEmail($email);
        if (!$user) {
            $user = User::fromEmail($email);
            $this->userRepository->add($user);
        }
        $application->addUser($user, $inviter);
        $this->eventBus->notifyAll($application->domainEvents);
    }
}