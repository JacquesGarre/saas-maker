<?php

declare(strict_types=1);

namespace App\Application\User\VerifyUserCommand;

use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\Shared\Id;
use App\Domain\Shared\EventBusInterface;
use App\Domain\User\Exception\UserAlreadyVerifiedException;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\VerificationToken;

final class VerifyUserCommandHandler {
    
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly EventBusInterface $eventBus
    ) {
    }

    // TODO: Integration test user already verified
    public function __invoke(VerifyUserCommand $command): void
    {
        $token = VerificationToken::fromString($command->token); 
        $user = $this->repository->findOneByVerificationToken($token);
        if (!$user) {
            throw new UserNotFoundException("Could not verify your email address");
        }
        if ($user->isVerified()->value) {
            throw new UserAlreadyVerifiedException("Email address already verified");
        }
        $user->verify();
        $this->eventBus->notifyAll($user->domainEvents);
    }
}