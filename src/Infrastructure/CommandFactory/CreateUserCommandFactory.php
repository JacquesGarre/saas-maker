<?php

declare(strict_types=1);

namespace App\Infrastructure\CommandFactory;

use App\Application\User\CreateUserCommand\CreateUserCommand;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Symfony\Component\HttpFoundation\Request;

final class CreateUserCommandFactory
{
    /**
     * @throws InvalidRequestContentException
     */
    public static function fromRequest(Request $request): CreateUserCommand
    {
        $content = json_decode($request->getContent(), true);
        if ($content === null) {
            throw new InvalidRequestContentException("Invalid json body");
        }
        return new CreateUserCommand(
            $content['id'] ?? null,
            $content['first_name'] ?? null,
            $content['last_name'] ?? null,
            $content['email'] ?? null,
            $content['password'] ?? null
        );
    }
}