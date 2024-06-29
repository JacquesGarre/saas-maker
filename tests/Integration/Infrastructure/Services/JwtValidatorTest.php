<?php

namespace App\Tests\Integration\Infrastructure\Services;

use App\Domain\Auth\Exception\InvalidJwtException;
use App\Infrastructure\Services\JwtGenerator;
use App\Infrastructure\Services\JwtValidator;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class JwtValidatorTest extends KernelTestCase
{
    private readonly JwtValidator $validator;
    private readonly JwtGenerator $generator;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->validator = $container->get(JwtValidator::class);
        $this->generator = $container->get(JwtGenerator::class);
    }

    public function testValidateSunnyCase(): void
    {
        $this->expectNotToPerformAssertions();
        $user = UserStub::random();
        $token = $this->generator->fromUser($user);
        $this->validator->validate($token);
    }

    public function testValidateWrongToken(): void
    {
        $token = Factory::create()->text();
        $this->expectException(InvalidJwtException::class);
        $this->validator->validate($token);
    }
}
