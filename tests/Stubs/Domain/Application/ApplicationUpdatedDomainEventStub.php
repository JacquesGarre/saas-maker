<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\Application;

use App\Domain\Application\ApplicationUpdatedDomainEvent;
use App\Tests\Stubs\Domain\Application\ApplicationStub;

final class ApplicationUpdatedDomainEventStub
{
    public static function random(): ApplicationUpdatedDomainEvent
    {
        $application = ApplicationStub::random();
        return ApplicationUpdatedDomainEvent::fromApplication($application);
    }
}