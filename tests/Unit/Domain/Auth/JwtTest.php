<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Auth;

use App\Domain\Auth\Exception\InvalidJwtException;
use App\Domain\Auth\Jwt;
use App\Domain\Auth\JwtGeneratorInterface;
use App\Domain\Auth\JwtValidatorInterface;
use App\Infrastructure\Services\JwtGenerator;
use App\Infrastructure\Services\JwtValidator;
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

    public function testFromStringSunnyCase(): void
    {   
        $faker = Factory::create();
        $appName = $faker->text();
        $jwtExpirationTime = $faker->numberBetween(50, 36000);
        $appSecret = $faker->text();
        $jwtGenerator = new JwtGenerator($appName, $jwtExpirationTime, $appSecret);

        $user = UserStub::random();
        $jwt = Jwt::fromUser($jwtGenerator, $user);

        $jwtValidator = new JwtValidator($appName, $appSecret);      
        $jwtFromString = Jwt::fromString($jwtValidator, $jwt->value);
        self::assertEquals($jwt->value, $jwtFromString->value);
    }

    public function testFromStringInvalidTokenException(): void
    {   
        $faker = Factory::create();
        $appName = $faker->text();
        $appSecret = $faker->text();
        $jwtValidator = new JwtValidator($appName, $appSecret); 
        $token = $faker->text();  
        $this->expectException(InvalidJwtException::class);
        Jwt::fromString($jwtValidator, $token);
    }
}