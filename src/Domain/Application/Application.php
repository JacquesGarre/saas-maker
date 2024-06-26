<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\Application\Exception\UserAlreadyAddedInApplicationException;
use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\DomainEventsTrait;
use App\Domain\Shared\Id;
use App\Domain\User\User;
use App\Domain\Application\ApplicationUserCollection;
use App\Domain\Application\ApplicationUser;
use App\Domain\Application\ApplicationUserAddedDomainEvent;
use App\Domain\Application\ApplicationUserRemovedDomainEvent;
use App\Domain\User\Exception\PermissionNotAllowedException;

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
        $this->users = $this->users ?? new ApplicationUserCollection();
    }

    public function users(): ?ApplicationUserCollection
    {
        return $this->users ?? new ApplicationUserCollection();
    }

    public function hasUser(User $user): bool
    {
        $applicationUser = $this->users->findByUser($user);
        return $applicationUser !== null;
    }

    public function setUsers(ApplicationUserCollection $users): void
    {
        $this->users = $users;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value->toString(),
            'name' => $this->name->value,
            'subdomain' => $this->subdomain->value,
            'created_at' => $this->createdAt->value(),
            'created_by' => $this->createdBy->toArray()
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
        $application->addUser($createdBy, $createdBy);
        $application->notifyDomainEvent(ApplicationCreatedDomainEvent::fromApplication($application));
        return $application;
    }

    public function addUser(User $user, User $inviter): void
    {
        if (!$user->id()->equals($inviter->id()) && !$this->hasUser($inviter)) {
            throw new PermissionNotAllowedException("Inviter is not a user of this application");
        }
        $applicationUser = ApplicationUser::create($this, $user);
        if ($this->hasUser($user)) {
            throw new UserAlreadyAddedInApplicationException("User already added");
        }
        $this->users->add($applicationUser);
        $this->notifyDomainEvent(ApplicationUserAddedDomainEvent::fromApplicationUser($applicationUser));
    }

    public function removeUser(User $user): void
    {
        if (!$this->hasUser($user)) {
            throw new UserNotFoundException("User is not a user of this application");
        }
        $applicationUser = $this->users->findByUser($user);
        $this->users->remove($applicationUser);
        $this->notifyDomainEvent(ApplicationUserRemovedDomainEvent::fromApplicationUser($applicationUser));
    }
}
