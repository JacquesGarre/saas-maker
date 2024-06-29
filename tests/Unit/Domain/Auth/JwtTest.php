<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Auth;

use App\Domain\Auth\Exception\InvalidJwtException;
use App\Domain\Auth\Jwt;
use App\Domain\Auth\JwtGeneratorInterface;
use App\Domain\Auth\JwtValidatorInterface;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class JwtTest extends TestCase {

    public function testFromUser(): void 
    {   
        $user = UserStub::random();
        $value = Factory::create()->text();
        $jwtGenerator = $this->createMock(JwtGeneratorInterface::class);
        $jwtGenerator->method('fromUser')->willReturn($value);
        $jwt = Jwt::fromUser($jwtGenerator, $user);
        self::assertEquals($value, $jwt->value);
    }

    public function testFromTokenSunnyCase(): void
    {   
        $jwtValidator = $this->createMock(JwtValidatorInterface::class);
        $jwtValidator->method('validate')->willReturn(true);

        $token = 'valid_token';
        $jwt = Jwt::fromToken($jwtValidator, $token);
        self::assertEquals($token, $jwt->value);
    }

    public function testFromTokenInvalidTokenException(): void
    {   
        $jwtValidator = $this->createMock(JwtValidatorInterface::class);
        $jwtValidator->method('validate')->willReturn(false);

        $token = 'invalid_token';
        $this->expectException(InvalidJwtException::class);
        Jwt::fromToken($jwtValidator, $token);
    }
}