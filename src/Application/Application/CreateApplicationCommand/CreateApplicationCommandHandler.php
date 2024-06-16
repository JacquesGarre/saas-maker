<?php

declare(strict_types=1);

namespace App\Application\Application\CreateApplicationCommand;

use App\Domain\Shared\Id;
use App\Domain\Shared\EventBusInterface;
use App\Domain\Application\Application;
use App\Domain\Application\ApplicationRepositoryInterface;
use App\Domain\Application\Exception\ApplicationAlreadyCreatedException;
use App\Domain\Application\Name;
use App\Domain\Application\Subdomain;

final class CreateApplicationCommandHandler {
    
    public function __construct(
        private readonly ApplicationRepositoryInterface $repository,
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
        $createdBy = new Id($command->createdById); 
        if ($this->repository->findOneBySubdomain($subdomain)) {
            throw new ApplicationAlreadyCreatedException("Application already exists");
        }
        $application = Application::create(
            $id, 
            $name, 
            $subdomain, 
            $createdBy
        );
        $this->repository->add($application);
        $this->eventBus->notifyAll($application->domainEvents);
    }
}