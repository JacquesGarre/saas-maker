<?php

declare(strict_types=1);

namespace App\Application\User\CreateUserCommand;

use App\Application\User\Exception\UserAlreadyCreatedException;
use App\Domain\Shared\Id;
use App\Domain\User\FirstName;
use App\Domain\User\LastName;
use App\Domain\User\Email;
use App\Domain\User\PasswordHash;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;

final class CreateUserCommandHandler {
    
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
        
    }

    /**
     * @throws UserAlreadyCreatedException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $id = new Id($command->id); 
        $firstName = new FirstName($command->firstName);
        $lastName = new LastName($command->lastName);
        $email = Email::fromString($command->email);
        $passwordHash = PasswordHash::fromPlainPassword($command->password);
        $user = User::create($id, $firstName, $lastName, $email, $passwordHash);

        if ($this->repository->findOneByEmailOrId($user->email, $user->id)) {
            throw new UserAlreadyCreatedException("User already exists");
        }

        $user = User::create($id, $firstName, $lastName, $email, $passwordHash);
        $this->repository->add($user);
    }
}