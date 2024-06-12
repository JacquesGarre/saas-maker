<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Shared;

use App\Domain\Shared\Exception\InvalidIdException;
use DateTime;
use PHPUnit\Framework\TestCase;
use App\Domain\Shared\Id;
use Faker\Factory;

final class IdTest extends TestCase
{
    public function testConstructor(): void
    {
        $value = Factory::create()->uuid();
        $id = new Id($value);
        self::assertEquals($value, $id->value->toString());
        $id = new Id();
        self::assertNotNull($id->value);
    }

    public function testAssertValid(): void
    {
        $value = 'not a uuid';
        $this->expectException(InvalidIdException::class);
        Id::assertValid($value);
    }

    public function testEquals(): void
    {
        $value = Factory::create()->uuid();
        $id1 = new Id($value);
        $id2 = new Id($value);
        $id3 = new Id();
        self::assertTrue($id1->equals($id2));
        self::assertFalse($id1->equals($id3));
    }
}