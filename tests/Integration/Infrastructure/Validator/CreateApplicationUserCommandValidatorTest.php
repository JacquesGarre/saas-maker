<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Validator;

use App\Application\Application\CreateApplicationUserCommand\CreateApplicationUserCommand;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateApplicationUserCommandValidatorTest extends KernelTestCase {

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
        $command = new CreateApplicationUserCommand(
            $faker->uuid(),
            $faker->email(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(0, $errors);
    }

    public function testIdNull(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationUserCommand(
            null,
            $faker->email(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testIdBlank(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationUserCommand(
            '',
            $faker->email(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testIdNotUuid(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationUserCommand(
            $faker->name(),
            $faker->email(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testEmailNull(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationUserCommand(
            $faker->uuid(),
            null,
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testEmailBlank(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationUserCommand(
            $faker->uuid(),
            '',
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testEmailNotEmail(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testInvitedByIdNull(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationUserCommand(
            $faker->uuid(),
            $faker->email(),
            null
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testInvitedByIdBlank(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationUserCommand(
            $faker->uuid(),
            $faker->email(),
            ''
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testInvitedByIdNotUuid(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationUserCommand(
            $faker->uuid(),
            $faker->email(),
            $faker->name(),
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

}