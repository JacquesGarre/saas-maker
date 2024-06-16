<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\Application;

use App\Domain\Application\Application;
use App\Tests\Stubs\Domain\Shared\IdStub;
use App\Domain\User\User;
use App\Tests\Stubs\Domain\User\UserStub;

final class ApplicationStub {

    public static function random(?User $user = null): Application
    {
        $id = IdStub::random();
        $name = NameStub::random();
        $subdomain = SubdomainStub::random();
        $user = $user ?? UserStub::random();
        return Application::create($id, $name, $subdomain, $user);
    }
}