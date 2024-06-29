<?php

declare(strict_types=1);

namespace App\Infrastructure\CommandFactory;

use App\Application\User\VerifyUserCommand\VerifyUserCommand;
use App\Domain\User\User;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Symfony\Component\HttpFoundation\Request;

final class VerifyUserCommandFactory
{
    /**
     * @throws InvalidRequestContentException
     */
    public static function fromRequest(Request $request): VerifyUserCommand
    {
        $content = json_decode($request->getContent(), true);
        if ($content === null) {
            throw new InvalidRequestContentException("Invalid json body");
        }
        return new VerifyUserCommand(
            $content['token'] ?? null
        );
    }
}