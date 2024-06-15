<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Shared;

use DateTime;
use PHPUnit\Framework\TestCase;
use App\Domain\Shared\UpdatedAt;

final class UpdatedAtTest extends TestCase
{
    public function testNow(): void
    {
        $value = (new DateTime())->format(DateTime::ATOM);
        $updatedAt = UpdatedAt::now();
        self::assertEquals($value, $updatedAt->value());
    }
}