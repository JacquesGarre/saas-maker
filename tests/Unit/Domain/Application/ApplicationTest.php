<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Application;

use App\Domain\Application\Application;
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
        $application = Application::create($id, $name, $subdomain);
        $createdAt = CreatedAt::now();
        $updatedAt = UpdatedAt::now();
        self::assertTrue($application->id->equals($id));
        self::assertEquals($name->value, $application->name->value);
        self::assertEquals($subdomain->value, $application->subdomain->value);
        self::assertEquals($createdAt->value(), $application->createdAt->value());
        self::assertEquals($updatedAt->value(), $application->updatedAt->value());
    }

    public function testUpdate(): void
    {
        $application = ApplicationStub::random();
        $idBefore = $application->id;
        $nameBefore = $application->name->value;
        $subdomainBefore = $application->subdomain->value;
        $createdAtBefore = $application->createdAt;
        $updatedAtBefore = $application->updatedAt;

        $newName = NameStub::random();
        $newSubdomain = SubdomainStub::random();
        $application = $application->update($newName, $newSubdomain);
        self::assertTrue($idBefore->equals($application->id));
        self::assertNotEquals($nameBefore, $application->name->value);
        self::assertNotEquals($subdomainBefore, $application->subdomain->value);
        self::assertEquals($createdAtBefore, $application->createdAt);
        self::assertNotEquals($updatedAtBefore, $application->updatedAt);
    }
}