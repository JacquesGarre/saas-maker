<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\DomainEventsTrait;
use App\Domain\Shared\Id;
use App\Domain\Shared\UpdatedAt;

final class Application {

    use DomainEventsTrait;

    private function __construct(
        public readonly Id $id,
        public readonly Name $name,
        public readonly Subdomain $subdomain,
        public readonly CreatedAt $createdAt,
        public readonly UpdatedAt $updatedAt,
        public readonly Id $createdBy,
        public readonly ?Id $updatedBy = null
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
            'updated_at' => $this->updatedAt->value(),
            'created_by' => $this->createdBy->value->toString(),
            'updated_by' => $this->updatedBy?->value->toString()
        ];
    }

    public static function create(
        Id $id,
        Name $name,
        Subdomain $subdomain,
        Id $createdBy
    ): self {
        $application = new self(
            $id,
            $name,
            $subdomain,
            CreatedAt::now(),
            UpdatedAt::now(),
            $createdBy
        );
        $application->notifyDomainEvent(ApplicationCreatedDomainEvent::fromApplication($application));
        return $application;
    }

    public function update(
        Name $name,
        Subdomain $subdomain,
        Id $updatedBy
    ): self {
        $application = new self(
            $this->id,
            $name,
            $subdomain,
            $this->createdAt,
            UpdatedAt::now(),
            $this->createdBy,
            $updatedBy
        );
        $application->notifyDomainEvent(ApplicationUpdatedDomainEvent::fromApplication($application));
        return $application;
    }
}
