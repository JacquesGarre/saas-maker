<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Validator;

use App\Application\Auth\LoginCommand\LoginCommand;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class LoginCommandValidatorTest extends KernelTestCase {

    private readonly ValidatorInterface $validator;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->validator = $container->get(ValidatorInterface::class);
    }

    public function testSunnyCase(): void
    {
        $faker = Factory::create();
        $command = new LoginCommand(
            $faker->email(),
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(0, $errors);
    }

    public function testEmailNull(): void
    {
        $faker = Factory::create();
        $command = new LoginCommand(
            null,
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testEmailBlank(): void
    {
        $faker = Factory::create();
        $command = new LoginCommand(
            '',
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testInvalidEmail(): void
    {
        $faker = Factory::create();
        $command = new LoginCommand(
            $faker->name(),
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testPasswordNull(): void
    {
        $faker = Factory::create();
        $command = new LoginCommand(
            $faker->email(),
            null,
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testPasswordBlank(): void
    {
        $faker = Factory::create();
        $command = new LoginCommand(
            $faker->email(),
            '',
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }
}