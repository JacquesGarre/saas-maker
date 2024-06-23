<?php

declare(strict_types=1);

namespace App\Infrastructure\CommandFactory;

use App\Application\Application\CreateApplicationUserCommand\CreateApplicationUserCommand;
use App\Domain\User\User;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Symfony\Component\HttpFoundation\Request;

final class CreateApplicationUserCommandFactory
{
    /**
     * @throws InvalidRequestContentException
     */
    public static function fromRequestAndUser(Request $request, User $user): CreateApplicationUserCommand
    {
        $content = json_decode($request->getContent(), true);
        if ($content === null) {
            throw new InvalidRequestContentException("Invalid json body");
        }
        return new CreateApplicationUserCommand(
            $request->attributes->get('uuid') ?? null,
            $content['email'] ?? null,
            $user->id()->value->toString()
        );
    }
}