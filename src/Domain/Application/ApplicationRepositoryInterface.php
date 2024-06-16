<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Shared\Id;

interface ApplicationRepositoryInterface {

    public function ofId(Id $id): ?Application;

    public function add(Application $application): void;

    public function remove(Application $application): void;

    public function findOneBySubdomain(Subdomain $subdomain): ?Application;
}