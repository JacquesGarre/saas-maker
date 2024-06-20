<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Application;

use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\Application\Application;
use App\Domain\Application\ApplicationCreatedDomainEvent;
use App\Domain\Application\Exception\UserAlreadyAddedInApplicationException;
use App\Domain\ApplicationUser\ApplicationUserAddedDomainEvent;
use App\Domain\ApplicationUser\ApplicationUserRemovedDomainEvent;
use App\Domain\Shared\CreatedAt;
use App\Tests\Stubs\Domain\Application\ApplicationStub;
use App\Tests\Stubs\Domain\Application\NameStub;
use App\Tests\Stubs\Domain\Application\SubdomainStub;
use App\Tests\Stubs\Domain\ApplicationUser\ApplicationUserCollectionStub;
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
        self::assertCount(2, $application->domainEvents);
        self::assertInstanceOf(ApplicationUserAddedDomainEvent::class, $application->domainEvents->first());
        self::assertInstanceOf(ApplicationCreatedDomainEvent::class, $application->domainEvents->last());
    }

    public function testSetUsers(): void
    {
        $collection = ApplicationUserCollectionStub::random();
        $application = ApplicationStub::random();
        $application->setUsers($collection);
        self::assertEquals($collection, $application->users());
    }

    public function testAddUser(): void
    {
        $application = ApplicationStub::random();
        $beforeCount = $application->users()->count();
        $user = UserStub::random();
        $application->addUser($user);       
        self::assertCount($beforeCount+1, $application->users());
        self::assertInstanceOf(ApplicationUserAddedDomainEvent::class, $application->domainEvents->last());
    }

    public function testUserAlreadyAddedException(): void
    {
        $application = ApplicationStub::random();
        $beforeCount = $application->users()->count();
        $user = UserStub::random();
        $application->addUser($user);   
        $this->expectException(UserAlreadyAddedInApplicationException::class);    
        $application->addUser($user);  
    }

    public function testRemoveUser(): void
    {
        $application = ApplicationStub::random();
        $application->removeUser($application->createdBy);       
        self::assertCount(0, $application->users());
        self::assertInstanceOf(ApplicationUserRemovedDomainEvent::class, $application->domainEvents->last());
    }

    public function testUserNotFoundException(): void
    {
        $application = ApplicationStub::random();
        $user = UserStub::random();  
        $this->expectException(UserNotFoundException::class);    
        $application->removeUser($user); 
    }

    public function testToArray(): void
    {
        $application = ApplicationStub::random();
        $expected = [
            'id' => $application->id->value->toString(),
            'name' => $application->name->value,
            'subdomain' => $application->subdomain->value,
            'created_at' => $application->createdAt->value(),
            'created_by' => $application->createdBy->toArray()
        ];
        self::assertEquals($expected, $application->toArray());
    }
}