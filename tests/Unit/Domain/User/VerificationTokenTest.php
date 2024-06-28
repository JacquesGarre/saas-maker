<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\Auth\JwtGeneratorInterface;
use App\Domain\User\VerificationToken;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class VerificationTokenTest extends TestCase {
    
    public function testFromUser(): void 
    {   
        $user = UserStub::random();
        $value = Factory::create()->text();
        $jwtGenerator = $this->createMock(JwtGeneratorInterface::class);
        $jwtGenerator->method('fromUser')->willReturn($value);
        $jwt = VerificationToken::fromUser($jwtGenerator, $user);
        self::assertEquals($value, $jwt->value);
    }
}