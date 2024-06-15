<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Validator;

use App\Application\User\UpdateUserCommand\UpdateUserCommand;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateUserCommandValidatorTest extends KernelTestCase {

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
        $command = new UpdateUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->name(),
            $faker->email(),
            $faker->password(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(0, $errors);
    }

    public function testIdNull(): void
    {
        $faker = Factory::create();
        $command = new UpdateUserCommand(
            null,
            $faker->name(),
            $faker->name(),
            $faker->email(),
            $faker->password(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testIdBlank(): void
    {
        $faker = Factory::create();
        $command = new UpdateUserCommand(
            '',
            $faker->name(),
            $faker->name(),
            $faker->email(),
            $faker->password(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testIdNotUuid(): void
    {
        $faker = Factory::create();
        $command = new UpdateUserCommand(
            $faker->name(),
            $faker->name(),
            $faker->name(),
            $faker->email(),
            $faker->password(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testUpdatedbyIdNull(): void
    {
        $faker = Factory::create();
        $command = new UpdateUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->name(),
            $faker->email(),
            $faker->password(),
            null
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testUpdatedByIdBlank(): void
    {
        $faker = Factory::create();
        $command = new UpdateUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->name(),
            $faker->email(),
            $faker->password(),
            ''
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testUpdatedByIdNotUuid(): void
    {
        $faker = Factory::create();
        $command = new UpdateUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->name(),
            $faker->email(),
            $faker->password(),
            $faker->name()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }
}