<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\Application;

use App\Domain\Application\Name;
use Faker\Factory;

final class NameStub {

    public static function random(): Name
    {
        $value = Factory::create()->name();
        return new Name($value);
    }
}