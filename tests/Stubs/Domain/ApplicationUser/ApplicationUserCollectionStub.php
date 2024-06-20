<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\ApplicationUser;

use App\Domain\ApplicationUser\ApplicationUserCollection;
use Faker\Factory;

final class ApplicationUserCollectionStub {

    public static function random(): ApplicationUserCollection
    {
        $faker = Factory::create();
        $collection = new ApplicationUserCollection();
        for ($i = 0; $i < $faker->randomNumber(1); $i++) {
            $collection->add(ApplicationUserStub::random());
        }
        return $collection;
    }
}