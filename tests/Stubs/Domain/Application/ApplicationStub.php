<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\Application;

use App\Domain\Application\Application;

final class ApplicationStub {

    public static function random(): Application
    {
        $name = NameStub::random();
        $subdomain = SubdomainStub::random();
        return Application::create($name, $subdomain);
    }
}