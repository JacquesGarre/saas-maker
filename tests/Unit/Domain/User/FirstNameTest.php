<?php

declare(strict_types= 1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\User\FirstName;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class FirstNameTest extends TestCase
{
    public function testConstructor(): void
    {
        $value = Factory::create()->text();
        $firstName = new FirstName($value);
        self::assertEquals($value, $firstName->value); 
    }
}