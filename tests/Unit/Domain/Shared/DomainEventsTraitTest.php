<?php

namespace App\Tests\Unit\Domain\Shared;

use App\Domain\Shared\DomainEventsTrait;
use App\Tests\Stubs\Domain\User\UserCreatedDomainEventStub;
use App\Tests\Stubs\Domain\User\UserUpdatedDomainEventStub;
use PHPUnit\Framework\TestCase;

class DomainEventsTraitTest extends TestCase
{
    public function testAddAndGetDomainEvents()
    {
        $object = new class {
            use DomainEventsTrait;
        };
        $object->initDomainEventCollection();
        $event1 = UserCreatedDomainEventStub::random();
        $event2 = UserUpdatedDomainEventStub::random();
        $object->notifyDomainEvent($event1);
        $object->notifyDomainEvent($event2);
        $this->assertEquals(2, $object->domainEvents->count());
        $this->assertSame($event1, $object->domainEvents[0]);
        $this->assertSame($event2, $object->domainEvents[1]);
        $object->clearDomainEvents();
        $this->assertEquals(0, $object->domainEvents->count());
    }
}
