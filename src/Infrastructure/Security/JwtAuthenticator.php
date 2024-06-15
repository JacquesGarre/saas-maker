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
    public const JWT_COOKIE = 'jwt_token';

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
        $token = $request->cookies->get(self::JWT_COOKIE);
        if (!$token) {
            throw new UnauthenticatedRequestException('No jwt token provided');
        }
        $jwt = new Jwt($token);
        $this->jwtValidator->assertValid($jwt);
        $user = $this->repository->findOneByJwt($jwt);
        if (!$user) {
            throw new UnauthenticatedRequestException('Wrong jwt token provided');
        }
        return $user;
    }
}