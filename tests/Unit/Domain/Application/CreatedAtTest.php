<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Application;

use DateTime;
use PHPUnit\Framework\TestCase;
use App\Domain\Application\CreatedAt;
use App\Domain\Application\Exception\InvalidCreatedAtException;

final class CreatedAtTest extends TestCase
{
    public function testFromString(): void
    {
        $value = (new DateTime())->format(DateTime::ATOM);
        $createdAt = CreatedAt::fromString($value);
        self::assertEquals($value, $createdAt->value());
    }

    public function testInvalidCreatedAtException(): void
    {
        $value = 'not a datetime';
        $this->expectException(InvalidCreatedAtException::class);
        CreatedAt::fromString($value);
    }
}