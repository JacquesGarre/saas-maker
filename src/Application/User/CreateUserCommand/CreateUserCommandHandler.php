<?php

declare(strict_types=1);

namespace App\Application\User\CreateUserCommand;

use App\Domain\Shared\Id;
use App\Domain\User\FirstName;
use App\Domain\User\LastName;
use App\Domain\User\Email;
use App\Domain\User\PasswordHash;

final class CreateUserCommandHandler {
    
    public function __construct()
    {
        
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $id = new Id($command->id);
        $firstName = new FirstName($command->firstName);
        $lastName = new LastName($command->lastName);
        $email = Email::fromString($command->email);
        $passwordHash = PasswordHash::fromPlainPassword($command->password);


    }

}