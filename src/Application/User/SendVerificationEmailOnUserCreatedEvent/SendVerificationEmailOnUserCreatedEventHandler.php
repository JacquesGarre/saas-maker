<?php

declare(strict_types=1);

namespace App\Application\User\SendVerificationEmailOnUserCreatedEvent;

use App\Application\User\Exception\UserNotFoundException;
use App\Domain\User\UserCreatedDomainEvent;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\Shared\Id;
use App\Domain\Email\EmailSenderInterface;

final class SendVerificationEmailOnUserCreatedEventHandler {
    
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        //private readonly EmailSenderInterface $emailSender
    ) {
        
    }
    
    public function __invoke(UserCreatedDomainEvent $event): void
    {
        $id = new Id($event->aggregateId());
        $user = $this->repository->ofId($id);
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
    }
}