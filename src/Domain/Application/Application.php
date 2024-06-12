<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\Id;
use App\Domain\Shared\UpdatedAt;

final class Application {

    private function __construct(
        public readonly Id $id,
        public readonly Name $name,
        public readonly Subdomain $subdomain,
        public readonly CreatedAt $createdAt,
        public readonly UpdatedAt $updatedAt,
        public readonly Id $createdBy,
        public readonly ?Id $updatedBy = null
    ) {
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
        return $application;
    }
}
