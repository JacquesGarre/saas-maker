<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\Application;

use App\Domain\Application\Application;
use App\Tests\Stubs\Domain\Shared\IdStub;

final class ApplicationStub {

    public static function random(): Application
    {
        $id = IdStub::random();
        $name = NameStub::random();
        $subdomain = SubdomainStub::random();
        $createdBy = IdStub::random();
        return Application::create($id, $name, $subdomain, $createdBy);
    }
}