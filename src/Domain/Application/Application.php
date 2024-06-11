<?php

declare(strict_types=1);

namespace App\Domain\Application;

final class Application {

    private function __construct(
        public readonly Name $name,
        public readonly Subdomain $subdomain,
        public readonly CreatedAt $createdAt,
        public readonly UpdatedAt $updatedAt
    ) {
    }

    public static function create(
        Name $name,
        Subdomain $subdomain
    ): self {
        $application = new self(
            $name,
            $subdomain,
            CreatedAt::now(),
            UpdatedAt::now()
        );
        return $application;
    }

    public function update(
        Name $name,
        Subdomain $subdomain
    ): self {
        $application = new self(
            $name,
            $subdomain,
            $this->createdAt,
            UpdatedAt::now()
        );
        return $application;
    }
}
