<?php

declare(strict_types=1);

namespace App\Application\Application\CreateApplicationCommand;

use App\Application\User\Exception\UserNotFoundException;
use App\Domain\Shared\Id;
use App\Domain\Shared\EventBusInterface;
use App\Domain\Application\Application;
use App\Domain\Application\ApplicationRepositoryInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\Application\Exception\ApplicationAlreadyCreatedException;
use App\Domain\Application\Name;
use App\Domain\Application\Subdomain;

final class CreateApplicationCommandHandler {
    
    public function __construct(
        private readonly ApplicationRepositoryInterface $applicationRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventBusInterface $eventBus
    ) {
        
    }

    /**
     * @throws ApplicationAlreadyCreatedException
     */
    public function __invoke(CreateApplicationCommand $command): void
    {
        $id = new Id($command->id); 
        $name = new Name($command->name);
        $subdomain = Subdomain::fromString($command->subdomain);
        if ($this->applicationRepository->ofId($id)) {
            throw new ApplicationAlreadyCreatedException("Application with same id already exists");
        }
        if ($this->applicationRepository->findOneBySubdomain($subdomain)) {
            throw new ApplicationAlreadyCreatedException("Application with same subdomain already exists");
        }
        $user = $this->userRepository->ofId(new Id($command->createdById));
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
        $application = Application::create(
            $id, 
            $name, 
            $subdomain, 
            $user
        );
        $this->applicationRepository->add($application);
        $this->eventBus->notifyAll($application->domainEvents);
    }
}