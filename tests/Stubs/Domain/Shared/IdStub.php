<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\Shared;

use App\Domain\Shared\Id;
use Faker\Factory;

final class IdStub {

    public static function random(): Id
    {
        $value = Factory::create()->uuid();
        return new Id($value);
    }
}