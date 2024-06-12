<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Shared;

use App\Domain\Shared\DomainEventCollection;
use App\Tests\Stubs\Domain\User\UserCreatedDomainEventStub;
use PHPUnit\Framework\TestCase;

final class DomainEventCollectionTest extends TestCase {

    public function testDomainEventCollection(): void
    {
        $events = new DomainEventCollection();
        self::assertEquals(0, $events->count());

        $domainEvent = UserCreatedDomainEventStub::random();
        $events->add($domainEvent);
        self::assertEquals(1, $events->count());

        $events->clear();
        self::assertEquals(0, $events->count());
    }
}