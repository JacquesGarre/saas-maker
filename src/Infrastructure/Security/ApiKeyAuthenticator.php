<?php

namespace App\Infrastructure\Security;

use App\Infrastructure\Security\Exception\UnauthenticatedRequestException;
use Symfony\Component\HttpFoundation\Request;

class ApiKeyAuthenticator
{
    public const API_KEY_HEADER = 'X-API-KEY';

    public function __construct(private readonly string $apiKey)
    {
    }

    /**
     * @throws UnauthenticatedRequestException
     */
    public function authenticate(Request $request): void
    {
        $apiKey = $request->headers->get(self::API_KEY_HEADER);
        if (!$apiKey) {
            throw new UnauthenticatedRequestException('No API key provided');
        }
        if ($apiKey !== $this->apiKey) {
            throw new UnauthenticatedRequestException('Invalid API key');
        }
    }
}