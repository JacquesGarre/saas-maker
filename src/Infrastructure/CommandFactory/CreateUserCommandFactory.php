<?php

declare(strict_types=1);

namespace App\Infrastructure\CommandFactory;

use App\Application\User\CreateUserCommand\CreateUserCommand;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateUserCommandFactory
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function fromRequest(Request $request): CreateUserCommand
    {
        $content = json_decode($request->getContent(), true);
        if ($content === null) {
            throw new InvalidRequestContentException("Invalid json body");
        }
        $command = new CreateUserCommand(
            $content['id'] ?? null,
            $content['first_name'] ?? null,
            $content['last_name'] ?? null,
            $content['email'] ?? null,
            $content['password'] ?? null
        );
        return $command;
    }
}