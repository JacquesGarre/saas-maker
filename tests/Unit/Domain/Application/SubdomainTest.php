<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Application;

use App\Domain\Application\Exception\InvalidSubdomainException;
use App\Domain\Application\Subdomain;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class SubdomainTest extends TestCase
{
    public function testFromString(): void
    {
        $value = Factory::create()->slug();
        $subdomain = Subdomain::fromString($value);
        self::assertEquals($value, $subdomain->value);
    }

    public function testInvalidSubdomainException(): void
    {
        $value = 'not a slug';
        $this->expectException(InvalidSubdomainException::class);
        Subdomain::assertValid($value);
    }
}