<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\Shared\TokenGeneratorInterface;
use App\Domain\User\VerificationToken;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class VerificationTokenTest extends TestCase {
    
    public function testGenerate(): void 
    {   
        $value = Factory::create()->text();
        $tokenGenerator = $this->createMock(TokenGeneratorInterface::class);
        $tokenGenerator->method('generate')->willReturn($value);
        $token = VerificationToken::generate($tokenGenerator);
        self::assertEquals($value, $token->value);
    }

    public function testFromString(): void
    {
        $value = Factory::create()->text();
        $token = VerificationToken::fromString($value);
        self::assertEquals($value, $token->value);
    }
}