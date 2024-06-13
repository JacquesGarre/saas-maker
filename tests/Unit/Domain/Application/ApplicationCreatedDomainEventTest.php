<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Application;

use App\Domain\Application\ApplicationCreatedDomainEvent;
use App\Tests\Stubs\Domain\Application\ApplicationStub;
use App\Tests\Stubs\Domain\Application\ApplicationCreatedDomainEventStub;
use DateTime;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

final class ApplicationCreatedDomainEventTest extends TestCase
{
    public function testFromApplication(): void
    {
        $application = ApplicationStub::random();
        $domainEvent = ApplicationCreatedDomainEvent::fromApplication($application);
        $now = (new DateTimeImmutable())->getTimestamp();
        self::assertNotNull($domainEvent->id);
        self::assertEquals($application->id->value->toString(), $domainEvent->aggregateId->toString());
        self::assertEquals(ApplicationCreatedDomainEvent::EVENT_TYPE, $domainEvent->type);
        self::assertEqualsWithDelta($now, $domainEvent->occuredAt->getTimestamp(), 1);
        self::assertEquals($application->toArray(), $domainEvent->data);
    }

    public function testOccuredAt(): void
    {
        $domainEvent = ApplicationCreatedDomainEventStub::random();
        self::assertEquals($domainEvent->occuredAt->format(DateTime::ATOM), $domainEvent->occuredAt());
    }
}