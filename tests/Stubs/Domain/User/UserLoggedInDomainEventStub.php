<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\User;

use App\Domain\User\UserLoggedInDomainEvent;
use App\Tests\Stubs\Domain\User\UserStub;

final class UserLoggedInDomainEventStub
{
    public static function random(): UserLoggedInDomainEvent
    {
        $user = UserStub::random();
        return UserLoggedInDomainEvent::fromUser($user);
    }
}