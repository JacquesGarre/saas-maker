<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Auth;

use App\Domain\Auth\Jwt;
use App\Domain\Auth\JwtGeneratorInterface;
use App\Domain\User\User;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class JwtTest extends TestCase {
    
    public function testConstructor(): void
    {
        $value = Factory::create()->text();
        $jwt = new Jwt($value);
        self::assertEquals($value, $jwt->value);
    }

    public function testFromUser(): void 
    {   
        $user = UserStub::random();
        $value = Factory::create()->text();
        $jwtGenerator = $this->createMock(JwtGeneratorInterface::class);
        $jwtGenerator->method('fromUser')->willReturn($value);
        $jwt = Jwt::fromUser($jwtGenerator, $user);
        self::assertEquals($value, $jwt->value);
    }
}