<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\Application;

use App\Domain\Application\Subdomain;
use Faker\Factory;

final class SubdomainStub {

    public static function random(): Subdomain
    {
        $value = Factory::create()->slug();
        return Subdomain::fromString($value);
    }
}