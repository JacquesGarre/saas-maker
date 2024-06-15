<?php

declare(strict_types=1);

namespace App\Infrastructure\CommandFactory;

use App\Application\Auth\LoginCommand\LoginCommand;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Symfony\Component\HttpFoundation\Request;

final class LoginCommandFactory
{
    /**
     * @throws InvalidRequestContentException
     */
    public static function fromRequest(Request $request): LoginCommand
    {
        $content = json_decode($request->getContent(), true);
        if ($content === null) {
            throw new InvalidRequestContentException("Invalid json body");
        }
        return new LoginCommand(
            $content['email'] ?? null,
            $content['password'] ?? null
        );
    }
}