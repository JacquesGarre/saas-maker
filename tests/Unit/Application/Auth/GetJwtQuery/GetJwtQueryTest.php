<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Auth\GetJwtQuery;

use App\Application\Auth\GetJwtQuery\GetJwtQuery;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class GetJwtQueryTest extends TestCase {

    public function testConstructor(): void
    {
        $faker = Factory::create();
        $email = $faker->email();
        $query = new GetJwtQuery($email);
        self::assertEquals($email, $query->email);
    }

    public function testConstructorWithNulls(): void
    {
        $query = new GetJwtQuery();
        self::assertNull($query->email);
    }
}