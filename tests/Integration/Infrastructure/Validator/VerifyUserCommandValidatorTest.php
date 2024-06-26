<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Validator;

use App\Application\User\VerifyUserCommand\VerifyUserCommand;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class VerifyUserCommandValidatorTest extends KernelTestCase {

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
        $command = new VerifyUserCommand($faker->text());
        $errors = $this->validator->validate($command);
        self::assertCount(0, $errors);
    }

    public function testTokenNull(): void
    {
        $command = new VerifyUserCommand();
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testTokenBlank(): void
    {
        $command = new VerifyUserCommand('');
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }
}