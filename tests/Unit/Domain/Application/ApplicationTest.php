<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Application;

use App\Domain\Application\Application;
use App\Domain\Application\ApplicationCreatedDomainEvent;
use App\Domain\Application\ApplicationUpdatedDomainEvent;
use App\Domain\Shared\CreatedAt;
use App\Domain\Shared\UpdatedAt;
use App\Tests\Stubs\Domain\Application\ApplicationStub;
use App\Tests\Stubs\Domain\Application\NameStub;
use App\Tests\Stubs\Domain\Application\SubdomainStub;
use App\Tests\Stubs\Domain\Shared\IdStub;
use PHPUnit\Framework\TestCase;

final class ApplicationTest extends TestCase
{
    public function testCreate(): void
    {
        $id = IdStub::random();
        $name = NameStub::random();
        $subdomain = SubdomainStub::random();
        $createdBy = IdStub::random();
        $application = Application::create($id, $name, $subdomain, $createdBy);
        $createdAt = CreatedAt::now();
        $updatedAt = UpdatedAt::now();
        self::assertTrue($application->id->equals($id));
        self::assertEquals($name->value, $application->name->value);
        self::assertEquals($subdomain->value, $application->subdomain->value);
        self::assertEquals($createdAt->value(), $application->createdAt->value());
        self::assertEquals($updatedAt->value(), $application->updatedAt->value());
        self::assertTrue($application->createdBy->equals($createdBy));
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
            'updated_at' => $application->updatedAt->value(),
            'created_by' => $application->createdBy->value->toString(),
            'updated_by' => $application->updatedBy?->value->toString()
        ];
        self::assertEquals($expected, $application->toArray());
    }

    public function testUpdate(): void
    {
        $applicationBefore = ApplicationStub::random();
        $newName = NameStub::random();
        $newSubdomain = SubdomainStub::random();
        $updatedBy = IdStub::random();
        $application = $applicationBefore->update($newName, $newSubdomain, $updatedBy);
        self::assertTrue($applicationBefore->id->equals($application->id));
        self::assertNotEquals($applicationBefore->name->value, $application->name->value);
        self::assertNotEquals($applicationBefore->subdomain->value, $application->subdomain->value);
        self::assertEquals($applicationBefore->createdAt, $application->createdAt);
        self::assertNotEquals($applicationBefore->updatedAt, $application->updatedAt);
        self::assertTrue($application->createdBy->equals($applicationBefore->createdBy));
        self::assertFalse($application->updatedBy->equals($applicationBefore->updatedBy));
        self::assertCount(1, $application->domainEvents);
        self::assertInstanceOf(ApplicationUpdatedDomainEvent::class, $application->domainEvents->last());
    }
}