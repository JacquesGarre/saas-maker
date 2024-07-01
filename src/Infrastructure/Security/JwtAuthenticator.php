<?php

namespace App\Infrastructure\Security;

use App\Domain\Auth\Jwt;
use App\Domain\Auth\JwtValidatorInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\User;
use App\Infrastructure\Security\Exception\UnauthenticatedRequestException;
use Symfony\Component\HttpFoundation\Request;

class JwtAuthenticator
{
    public const HEADER = 'Authorization';

    public function __construct(
        private readonly JwtValidatorInterface $jwtValidator,
        private readonly UserRepositoryInterface $repository
    ) {
    }

    /**
     * @throws UnauthenticatedRequestException
     */
    public function authenticate(Request $request): User
    {
        $authHeader = $request->headers->get(self::HEADER);
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            throw new UnauthenticatedRequestException('No jwt token provided');
        }
        $token = $matches[1];
        if (!$token) {
            throw new UnauthenticatedRequestException('No jwt token provided');
        }
        $jwt = Jwt::fromString($this->jwtValidator, $token);
        $user = $this->repository->findOneByJwt($jwt);
        if (!$user) {
            throw new UnauthenticatedRequestException('Wrong jwt token provided');
        }
        return $user;
    }
}