<?php

declare(strict_types= 1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\User\LastName;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class LastNameTest extends TestCase
{
    public function testConstructor(): void
    {
        $value = Factory::create()->text();
        $lastName = new LastName($value);
        self::assertEquals($value, $lastName->value); 
    }
}