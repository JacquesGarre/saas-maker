<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\User\UserInvitedDomainEvent;
use App\Tests\Stubs\Domain\Application\ApplicationStub;
use App\Tests\Stubs\Domain\User\UserStub;
use DateTime;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

final class UserInvitedDomainEventTest extends TestCase
{
    public function testFromUserAndApplication(): void
    {
        $user = UserStub::random();
        $application = ApplicationStub::random();
        $domainEvent = UserInvitedDomainEvent::fromUserAndApplication($user, $application);
        $now = (new DateTimeImmutable())->getTimestamp();
        self::assertNotNull($domainEvent->id);
        self::assertEquals($user->id()->value->toString(), $domainEvent->aggregateId->toString());
        self::assertEquals(UserInvitedDomainEvent::EVENT_TYPE, $domainEvent->type);
        self::assertEqualsWithDelta($now, $domainEvent->occuredAt->getTimestamp(), 1);
        self::assertEquals($domainEvent->occuredAt->format(DateTime::ATOM), $domainEvent->occuredAt());
        self::assertEquals([
            'user' =>  $user->toArray(),
            'application' => $application->toArray()
        ], $domainEvent->data);
    }
}