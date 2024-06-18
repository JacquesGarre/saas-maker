<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Application\User\Exception\UserNotFoundException;
use App\Domain\Application\Exception\UserAlreadyAddedInApplicationException;
use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\DomainEventsTrait;
use App\Domain\Shared\Id;
use App\Domain\User\User;

final class Application {

    use DomainEventsTrait;

    private function __construct(
        public readonly Id $id,
        public readonly Name $name,
        public readonly Subdomain $subdomain,
        public readonly CreatedAt $createdAt,
        public readonly User $createdBy,
        private ?ApplicationUserCollection $users = null
    ) {
        $this->initDomainEventCollection();
        $this->users = $this->users ?? new ApplicationUserCollection([]);
    }

    public function users(): ?ApplicationUserCollection
    {
        return $this->users ?? new ApplicationUserCollection([]);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value->toString(),
            'name' => $this->name->value,
            'subdomain' => $this->subdomain->value,
            'created_at' => $this->createdAt->value(),
            'created_by' => $this->createdBy->toArray(),
            'users' => $this->users->toArray()
        ];
    }

    public static function create(
        Id $id,
        Name $name,
        Subdomain $subdomain,
        User $createdBy
    ): self {
        $application = new self(
            $id,
            $name,
            $subdomain,
            CreatedAt::now(),
            $createdBy
        );
        $applicationUser = ApplicationUser::create($application, $createdBy);
        //$application->users->add($applicationUser);
        $application->notifyDomainEvent(ApplicationCreatedDomainEvent::fromApplication($application));
        return $application;
    }

    public function addUser(User $user): void
    {
        $alreadyExistingApplicationUser = $this->users->filter(fn(ApplicationUser $au) => $au->user->id()->equals($user->id()))->first();
        if ($alreadyExistingApplicationUser) {
            throw new UserAlreadyAddedInApplicationException("User already exists");
        }
        $applicationUser = ApplicationUser::create($this, $user);
        $this->users->add($applicationUser);
        $this->notifyDomainEvent(ApplicationUserAddedDomainEvent::fromApplicationUser($applicationUser));
    }

    public function removeUser(User $user): void
    {   
        $applicationUser = $this->users->filter(fn(ApplicationUser $au) => $au->user->id()->equals($user->id()))->first();
        if (!$applicationUser) {
            throw new UserNotFoundException("Application user not found");
        }
        $this->users->remove($applicationUser);
        $this->notifyDomainEvent(ApplicationUserRemovedDomainEvent::fromApplicationUser($applicationUser));
    }
}
