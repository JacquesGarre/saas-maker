<?php

declare(strict_types=1);

namespace App\Domain\Application;

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
        public readonly User $createdBy
    ) {
        $this->initDomainEventCollection();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value->toString(),
            'name' => $this->name->value,
            'subdomain' => $this->subdomain->value,
            'created_at' => $this->createdAt->value(),
            'created_by' => $this->createdBy->toArray(),
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
        $application->notifyDomainEvent(ApplicationCreatedDomainEvent::fromApplication($application));
        return $application;
    }
}
