<?php

namespace App\Infrastructure\Security;

use App\Infrastructure\Security\Exception\UnauthenticatedRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge;


class ApiKeyAuthenticator extends AbstractAuthenticator
{
    public const API_KEY_HEADER = 'X-API-KEY';

    public function __construct(private readonly string $apiKey)
    {
    }

    public function supports(Request $request): ?bool
    {
        return true;
    }

    /**
     * @throws UnauthenticatedRequestException
     */
    public function authenticate(Request $request): Passport
    {
        $apiKey = $request->headers->get(self::API_KEY_HEADER);
        if (!$apiKey) {
            throw new UnauthenticatedRequestException('No API key provided');
        }
        if ($apiKey !== $this->apiKey) {
            throw new UnauthenticatedRequestException('Invalid API key');
        }
        return new Passport(); // TODO
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /**
     * @throws UnauthenticatedRequestException
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new UnauthenticatedRequestException($exception->getMessage());
    }
}