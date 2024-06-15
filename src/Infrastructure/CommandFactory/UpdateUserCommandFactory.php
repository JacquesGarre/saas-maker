<?php

declare(strict_types=1);

namespace App\Infrastructure\CommandFactory;

use App\Application\User\UpdateUserCommand\UpdateUserCommand;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Symfony\Component\HttpFoundation\Request;

final class UpdateUserCommandFactory
{
    /**
     * @throws InvalidRequestContentException
     */
    public static function fromRequest(Request $request): UpdateUserCommand
    {
        $content = json_decode($request->getContent(), true);
        if ($content === null) {
            throw new InvalidRequestContentException("Invalid json body");
        }
        return new UpdateUserCommand(
            $content['id'] ?? null,
            $content['first_name'] ?? null,
            $content['last_name'] ?? null,
            $content['email'] ?? null,
            $content['password'] ?? null
        );
    }
}