<?php

declare(strict_types=1);

namespace App\Infrastructure\CommandFactory;

use App\Application\Application\CreateApplicationCommand\CreateApplicationCommand;
use App\Domain\User\User;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Symfony\Component\HttpFoundation\Request;

final class CreateApplicationCommandFactory
{
    /**
     * @throws InvalidRequestContentException
     */
    public static function fromRequestAndUser(Request $request, User $user): CreateApplicationCommand
    {
        $content = json_decode($request->getContent(), true);
        if ($content === null) {
            throw new InvalidRequestContentException("Invalid json body");
        }
        return new CreateApplicationCommand(
            $content['id'] ?? null,
            $content['name'] ?? null,
            $content['subdomain'] ?? null,
            $user->id->value->toString()
        );
    }
}