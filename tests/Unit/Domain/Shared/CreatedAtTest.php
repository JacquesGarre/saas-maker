<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Shared;

use DateTime;
use PHPUnit\Framework\TestCase;
use App\Domain\Shared\CreatedAt;

final class CreatedAtTest extends TestCase
{
    public function testNow(): void
    {
        $value = (new DateTime())->format(DateTime::ATOM);
        $createdAt = CreatedAt::now();
        self::assertEquals($value, $createdAt->value());
    }
}