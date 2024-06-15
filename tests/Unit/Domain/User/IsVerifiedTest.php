<?php

declare(strict_types= 1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\User\IsVerified;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class IsVerifiedTest extends TestCase
{
    public function testConstructor(): void
    {
        $value = Factory::create()->boolean();
        $isVerified = new IsVerified($value);
        self::assertEquals($value, $isVerified->value); 
    }
}