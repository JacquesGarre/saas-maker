<?php

declare(strict_types=1);

namespace App\Application\Auth\GetJwtQuery;

use App\Domain\User\Email;
use App\Domain\User\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final class GetJwtQueryHandler {

    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    public function __invoke(GetJwtQuery $query): GetJwtQueryResult
    {
        $email = Email::fromString($query->email);
        $user = $this->repository->findOneByEmail($email);
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
        return new GetJwtQueryResult($user->jwt?->value);
    }
}