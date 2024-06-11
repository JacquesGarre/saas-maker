<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Application;

use App\Domain\Application\Application;
use App\Domain\Application\CreatedAt;
use App\Domain\Application\UpdatedAt;
use App\Tests\Stubs\Domain\Application\ApplicationStub;
use App\Tests\Stubs\Domain\Application\NameStub;
use App\Tests\Stubs\Domain\Application\SubdomainStub;
use PHPUnit\Framework\TestCase;

final class ApplicationTest extends TestCase
{
    public function testCreate(): void
    {
        $name = NameStub::random();
        $subdomain = SubdomainStub::random();
        $application = Application::create($name, $subdomain);
        $createdAt = CreatedAt::now();
        $updatedAt = UpdatedAt::now();
        self::assertEquals($name->value, $application->name->value);
        self::assertEquals($subdomain->value, $application->subdomain->value);
        self::assertEquals($createdAt->value(), $application->createdAt->value());
        self::assertEquals($updatedAt->value(), $application->updatedAt->value());
    }

    public function testUpdate(): void
    {
        $application = ApplicationStub::random();
        $nameBefore = $application->name->value;
        $subdomainBefore = $application->subdomain->value;
        $createdAtBefore = $application->createdAt->value();
        $updatedAtBefore = $application->updatedAt->value();

        sleep(1);
        $newName = NameStub::random();
        $newSubdomain = SubdomainStub::random();
        $application = $application->update($newName, $newSubdomain);
        self::assertNotEquals($nameBefore, $application->name->value);
        self::assertNotEquals($subdomainBefore, $application->subdomain->value);
        self::assertEquals($createdAtBefore, $application->createdAt->value());
        self::assertNotEquals($updatedAtBefore, $application->updatedAt->value());
    }
}