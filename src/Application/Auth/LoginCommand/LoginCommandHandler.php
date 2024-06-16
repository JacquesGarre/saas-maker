<?php

declare(strict_types=1);

namespace App\Application\Auth\LoginCommand;

use App\Application\Auth\Exception\InvalidCredentialsException;
use App\Domain\Auth\JwtGeneratorInterface;
use App\Domain\Shared\EventBusInterface;
use App\Domain\Shared\EmailAddress;
use App\Domain\User\UserRepositoryInterface;

final class LoginCommandHandler {
    
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly EventBusInterface $eventBus,
        private readonly JwtGeneratorInterface $jwtGenerator
    ) {
        
    }

    public function __invoke(LoginCommand $command): void
    {
        $email = EmailAddress::fromString($command->email);
        $user = $this->repository->findOneByEmail($email);
        if (!$user) {
            throw new InvalidCredentialsException("User not found");
        }
        $user->login($this->jwtGenerator, $command->password);
        $this->eventBus->notifyAll($user->domainEvents);
    }
}