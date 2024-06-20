<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Application;

use App\Domain\Application\ApplicationUserRemovedDomainEvent;
use App\Tests\Stubs\Domain\Application\ApplicationUserStub;
use DateTime;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

final class ApplicationUserRemovedDomainEventTest extends TestCase
{
    public function testFromApplication(): void
    {
        $applicationUser = ApplicationUserStub::random();
        $domainEvent = ApplicationUserRemovedDomainEvent::fromApplicationUser($applicationUser);
        $now = (new DateTimeImmutable())->getTimestamp();
        self::assertNotNull($domainEvent->id);
        self::assertEquals($applicationUser->application->id->value->toString(), $domainEvent->aggregateId->toString());
        self::assertEquals(ApplicationUserRemovedDomainEvent::EVENT_TYPE, $domainEvent->type);
        self::assertEqualsWithDelta($now, $domainEvent->occuredAt->getTimestamp(), 1);
        self::assertEquals($applicationUser->toArray(), $domainEvent->data);
        self::assertEquals($domainEvent->occuredAt->format(DateTime::ATOM), $domainEvent->occuredAt());
    }
}