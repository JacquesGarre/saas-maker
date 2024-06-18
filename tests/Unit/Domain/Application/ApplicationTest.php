<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Application;

use App\Domain\Application\Application;
use App\Domain\Application\ApplicationCreatedDomainEvent;
use App\Domain\Shared\CreatedAt;
use App\Tests\Stubs\Domain\Application\ApplicationStub;
use App\Tests\Stubs\Domain\Application\NameStub;
use App\Tests\Stubs\Domain\Application\SubdomainStub;
use App\Tests\Stubs\Domain\Shared\IdStub;
use App\Tests\Stubs\Domain\User\UserStub;
use PHPUnit\Framework\TestCase;

final class ApplicationTest extends TestCase
{
    public function testCreate(): void
    {
        $id = IdStub::random();
        $name = NameStub::random();
        $subdomain = SubdomainStub::random();
        $createdBy = UserStub::random();
        $application = Application::create($id, $name, $subdomain, $createdBy);
        $createdAt = CreatedAt::now();
        self::assertTrue($application->id->equals($id));
        self::assertEquals($name->value, $application->name->value);
        self::assertEquals($subdomain->value, $application->subdomain->value);
        self::assertEquals($createdAt->value(), $application->createdAt->value());
        self::assertEquals($createdBy, $application->createdBy);
        self::assertCount(1, $application->users());
        self::assertEquals($createdBy, $application->users()->first()->user);
        self::assertCount(1, $application->domainEvents);
        self::assertInstanceOf(ApplicationCreatedDomainEvent::class, $application->domainEvents->last());
    }

    public function testToArray(): void
    {
        $application = ApplicationStub::random();
        $expected = [
            'id' => $application->id->value->toString(),
            'name' => $application->name->value,
            'subdomain' => $application->subdomain->value,
            'created_at' => $application->createdAt->value(),
            'created_by' => $application->createdBy->toArray(),
            'users' => $application->users()->toArray()
        ];
        self::assertEquals($expected, $application->toArray());
    }
}