<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\User\UserVerificationTokenGeneratedDomainEvent;
use App\Tests\Stubs\Domain\User\UserStub;
use DateTime;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

final class UserVerificationTokenGeneratedDomainEventTest extends TestCase
{
    public function testFromUser(): void
    {
        $user = UserStub::random();
        $domainEvent = UserVerificationTokenGeneratedDomainEvent::fromUser($user);
        $now = (new DateTimeImmutable())->getTimestamp();
        self::assertNotNull($domainEvent->id);
        self::assertEquals($user->id()->value->toString(), $domainEvent->aggregateId->toString());
        self::assertEquals(UserVerificationTokenGeneratedDomainEvent::EVENT_TYPE, $domainEvent->type);
        self::assertEqualsWithDelta($now, $domainEvent->occuredAt->getTimestamp(), 1);
        self::assertEquals($user->toArray(), $domainEvent->data);
        self::assertEquals($domainEvent->occuredAt->format(DateTime::ATOM), $domainEvent->occuredAt());
    }
}
