<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Application;

use App\Domain\Application\ApplicationUserCollection;
use App\Tests\Stubs\Domain\Application\ApplicationUserStub;
use PHPUnit\Framework\TestCase;

final class ApplicationUserCollectionTest extends TestCase
{
    public function testAdd(): void
    {
        $applicationUser = ApplicationUserStub::random();
        $collection = new ApplicationUserCollection();
        $collection->add($applicationUser);
        self::assertEquals($applicationUser, $collection->first());
        self::assertCount(1, $collection);
    }

    public function testRemove(): void
    {
        $applicationUser = ApplicationUserStub::random();
        $collection = new ApplicationUserCollection();
        $collection->add($applicationUser);
        self::assertEquals($applicationUser, $collection->first());
        self::assertCount(1, $collection);

        $collection->remove($applicationUser);
        self::assertCount(0, $collection);
    }

    public function testCount(): void
    {
        $applicationUser = ApplicationUserStub::random();
        $collection = new ApplicationUserCollection();
        self::assertEquals(0, $collection->count());
        $collection->add($applicationUser);
        self::assertEquals(1, $collection->count());
    }

    public function testFirst(): void
    {   
        $applicationUser = ApplicationUserStub::random();
        $collection = new ApplicationUserCollection();
        $collection->add($applicationUser);

        $applicationUser2 = ApplicationUserStub::random();
        $collection->add($applicationUser2);

        $applicationUser3 = ApplicationUserStub::random();
        $collection->add($applicationUser3);
        self::assertEquals($applicationUser, $collection->first());
    }

    public function testLast(): void
    {   
        $applicationUser = ApplicationUserStub::random();
        $collection = new ApplicationUserCollection();
        $collection->add($applicationUser);

        $applicationUser2 = ApplicationUserStub::random();
        $collection->add($applicationUser2);

        $applicationUser3 = ApplicationUserStub::random();
        $collection->add($applicationUser3);
        self::assertEquals($applicationUser3, $collection->last());
    }

    public function testToArray(): void
    {
        $applicationUser = ApplicationUserStub::random();
        $collection = new ApplicationUserCollection();
        $collection->add($applicationUser);
        self::assertEquals([$applicationUser->toArray()], $collection->toArray());
    }

    public function testFindByUser(): void
    {
        $applicationUser = ApplicationUserStub::random();
        $collection = new ApplicationUserCollection();
        $collection->add($applicationUser);

        $applicationUser2 = ApplicationUserStub::random();
        $collection->add($applicationUser2);

        $applicationUser3 = ApplicationUserStub::random();
        $collection->add($applicationUser3);

        $foundApplicationUser = $collection->findByUser($applicationUser2->user);
        self::assertEquals($applicationUser2, $foundApplicationUser);
    }
}