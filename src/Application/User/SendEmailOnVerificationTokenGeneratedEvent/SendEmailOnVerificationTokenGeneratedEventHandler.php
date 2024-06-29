<?php

declare(strict_types=1);

namespace App\Application\User\SendEmailOnVerificationTokenGeneratedEvent;

use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\Shared\Id;
use App\Domain\Email\EmailSenderInterface;
use App\Domain\Email\TemplateRendererInterface;
use App\Domain\Email\UserVerificationEmail;
use App\Domain\Shared\EmailAddress;
use App\Domain\Shared\EventBusInterface;
use App\Domain\User\UserVerificationTokenGeneratedDomainEvent;

final class SendEmailOnVerificationTokenGeneratedEventHandler {
    
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly TemplateRendererInterface $templateRenderer,
        private readonly EmailSenderInterface $emailSender,
        private readonly EventBusInterface $eventBus,
        private readonly string $emailDefaultSender
    ) {
    }
    
    public function __invoke(UserVerificationTokenGeneratedDomainEvent $event): void
    {
        $id = new Id($event->aggregateId());
        $user = $this->repository->ofId($id);
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
        if ($user->isVerified()->value) {
            return;
        }
        $email = UserVerificationEmail::fromUser(
            $this->emailSender,
            $this->templateRenderer,
            EmailAddress::fromString($this->emailDefaultSender),
            $user
        );
        $email->send();
        $this->eventBus->notifyAll($email->domainEvents);
    }
}