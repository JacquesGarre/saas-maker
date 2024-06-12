<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\Application;

use App\Domain\Application\ApplicationCreatedDomainEvent;
use App\Tests\Stubs\Domain\Application\ApplicationStub;

final class ApplicationCreatedDomainEventStub
{
    public static function random(): ApplicationCreatedDomainEvent
    {
        $application = ApplicationStub::random();
        return ApplicationCreatedDomainEvent::fromApplication($application);
    }
}