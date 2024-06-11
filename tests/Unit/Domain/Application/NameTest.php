<?php

declare(strict_types= 1);

namespace App\Tests\Unit\Domain\Application;

use App\Domain\Application\Name;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class NameTest extends TestCase
{
    public function testConstructor(): void
    {
        $value = Factory::create()->text();
        $name = new Name($value);
        self::assertEquals($value, $name->value); 
    }
}