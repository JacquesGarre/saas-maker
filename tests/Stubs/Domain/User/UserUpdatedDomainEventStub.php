<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\User;

use App\Domain\User\UserUpdatedDomainEvent;
use App\Tests\Stubs\Domain\User\UserStub;

final class UserUpdatedDomainEventStub
{
    public static function random(): UserUpdatedDomainEvent
    {
        $user = UserStub::random();
        return UserUpdatedDomainEvent::fromUser($user);
    }
}