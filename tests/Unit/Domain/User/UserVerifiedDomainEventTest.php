<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\User\UserVerifiedDomainEvent;
use App\Tests\Stubs\Domain\User\UserStub;
use App\Tests\Stubs\Domain\User\UserVerifiedDomainEventStub;
use DateTime;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

final class UserVerifiedDomainEventTest extends TestCase
{
    public function testFromUser(): void
    {
        $user = UserStub::random();
        $domainEvent = UserVerifiedDomainEvent::fromUser($user);
        $now = (new DateTimeImmutable())->format(DateTime::ATOM);
        self::assertNotNull($domainEvent->id);
        self::assertEquals($user->id->value->toString(), $domainEvent->aggregateId->toString());
        self::assertEquals(UserVerifiedDomainEvent::EVENT_TYPE, $domainEvent->type);
        self::assertEquals($now, $domainEvent->occuredAt->format(DateTime::ATOM));
        self::assertEquals($user->toArray(), $domainEvent->data);
    }

    public function testOccuredAt(): void
    {
        $now = (new DateTimeImmutable())->format(DateTime::ATOM);
        $domainEvent = UserVerifiedDomainEventStub::random();
        self::assertEquals($now, $domainEvent->occuredAt());
    }
}