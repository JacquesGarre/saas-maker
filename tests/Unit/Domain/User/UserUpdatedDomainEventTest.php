<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\User\UserUpdatedDomainEvent;
use App\Tests\Stubs\Domain\User\UserStub;
use App\Tests\Stubs\Domain\User\UserUpdatedDomainEventStub;
use DateTime;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

final class UserUpdatedDomainEventTest extends TestCase
{
    public function testFromUser(): void
    {
        $user = UserStub::random();
        $domainEvent = UserUpdatedDomainEvent::fromUser($user);
        $now = (new DateTimeImmutable())->getTimestamp();
        self::assertNotNull($domainEvent->id);
        self::assertEquals($user->id()->value->toString(), $domainEvent->aggregateId->toString());
        self::assertEquals(UserUpdatedDomainEvent::EVENT_TYPE, $domainEvent->type);
        self::assertEqualsWithDelta($now, $domainEvent->occuredAt->getTimestamp(), 1);
        self::assertEquals($user->toArray(), $domainEvent->data);
    }

    public function testOccuredAt(): void
    {
        $domainEvent = UserUpdatedDomainEventStub::random();
        self::assertEquals($domainEvent->occuredAt->format(DateTime::ATOM), $domainEvent->occuredAt());
    }
}