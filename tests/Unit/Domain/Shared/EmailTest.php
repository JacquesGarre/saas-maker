<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Shared;

use App\Domain\Shared\Exception\InvalidEmailException;
use App\Domain\Shared\EmailAddress;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class EmailAddressTest extends TestCase
{
    public function testFromString(): void
    {
        $value = Factory::create()->email();
        $email = EmailAddress::fromString($value);
        self::assertEquals($value, $email->value);
    }

    public function testAssertValid(): void
    {
        $value = 'not an email';
        $this->expectException(InvalidEmailException::class);
        EmailAddress::assertValid($value);
    }
}