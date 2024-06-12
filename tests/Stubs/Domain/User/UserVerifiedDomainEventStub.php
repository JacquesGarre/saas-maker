<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\User;

use App\Domain\User\UserVerifiedDomainEvent;
use App\Tests\Stubs\Domain\User\UserStub;

final class UserVerifiedDomainEventStub
{
    public static function random(): UserVerifiedDomainEvent
    {
        $user = UserStub::random();
        return UserVerifiedDomainEvent::fromUser($user);
    }
}