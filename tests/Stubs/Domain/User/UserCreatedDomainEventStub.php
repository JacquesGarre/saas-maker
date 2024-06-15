<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\User;

use App\Domain\User\UserCreatedDomainEvent;
use App\Tests\Stubs\Domain\User\UserStub;

final class UserCreatedDomainEventStub
{
    public static function random(): UserCreatedDomainEvent
    {
        $user = UserStub::random();
        return UserCreatedDomainEvent::fromUser($user);
    }
}