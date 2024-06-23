<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Application\Application;
use App\Domain\Shared\DomainEvent;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class UserInvitedDomainEvent extends DomainEvent
{
    public const EVENT_TYPE = 'UserInvitedDomainEvent';

    private function __construct(public readonly User $user, public readonly Application $application) 
    {
        parent::__construct(
            Uuid::uuid4(),
            $user->id()->value,
            self::EVENT_TYPE,
            new DateTimeImmutable(),
            [
                'user' =>  $user->toArray(),
                'application' => $application->toArray()
            ]
        );
    }

    public static function fromUserAndApplication(User $user, Application $application): self
    {
        return new self($user, $application);
    }
}