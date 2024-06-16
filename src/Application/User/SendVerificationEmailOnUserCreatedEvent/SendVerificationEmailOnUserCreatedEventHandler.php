<?php

declare(strict_types=1);

namespace App\Application\User\SendVerificationEmailOnUserCreatedEvent;

use App\Application\User\Exception\UserNotFoundException;
use App\Domain\User\UserCreatedDomainEvent;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\Shared\Id;
use App\Domain\Email\EmailSenderInterface;
use App\Domain\Email\From;
use App\Domain\Email\TemplateRendererInterface;
use App\Domain\Email\UserVerificationEmail;
use App\Domain\Shared\EventBusInterface;

final class SendVerificationEmailOnUserCreatedEventHandler {
    
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly TemplateRendererInterface $templateRenderer,
        private readonly EmailSenderInterface $emailSender,
        private readonly EventBusInterface $eventBus,
        private readonly string $emailDefaultSender
    ) {
    }
    
    public function __invoke(UserCreatedDomainEvent $event): void
    {
        $id = new Id($event->aggregateId());
        $user = $this->repository->ofId($id);
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
        $email = UserVerificationEmail::fromUser(
            $this->emailSender,
            $this->templateRenderer,
            From::fromString($this->emailDefaultSender),
            $user
        );
        $email->send();
        $this->eventBus->notifyAll($email->domainEvents);
    }
}