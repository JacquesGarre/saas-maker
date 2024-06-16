<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\Shared\Exception\InvalidEmailException;
use App\Domain\User\Email;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function testFromString(): void
    {
        $value = Factory::create()->email();
        $email = Email::fromString($value);
        self::assertEquals($value, $email->value);
    }

    public function testAssertValid(): void
    {
        $value = 'not an email';
        $this->expectException(InvalidEmailException::class);
        Email::assertValid($value);
    }
}