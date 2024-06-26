<?php

declare(strict_types=1);

namespace App\Application\User\VerifyUserCommand;

use App\Domain\User\Exception\UserNotFoundException;
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

    public function __invoke(VerifyUserCommand $command): void
    {
        $token = VerificationToken::fromString($command->token); 
        $user = $this->repository->findOneByVerificationToken($token);
        if (!$user) {
            throw new UserNotFoundException("We were unable to verify your email address");
        }
        if ($user->isVerified()->value) {
            throw new UserAlreadyVerifiedException("Your email address has already been verified");
        }
        $user->verify();
        $this->eventBus->notifyAll($user->domainEvents);
    }
}