<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Validator;

use App\Application\Application\CreateApplicationCommand\CreateApplicationCommand;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateApplicationCommandValidatorTest extends KernelTestCase {

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
        $command = new CreateApplicationCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->slug(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(0, $errors);
    }

    public function testIdNull(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationCommand(
            null,
            $faker->name(),
            $faker->slug(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testIdBlank(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationCommand(
            '',
            $faker->name(),
            $faker->slug(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testIdNotUuid(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationCommand(
            $faker->name(),
            $faker->name(),
            $faker->slug(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testNameNull(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationCommand(
            $faker->uuid(),
            null,
            $faker->slug(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testNameBlank(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationCommand(
            $faker->uuid(),
            '',
            $faker->slug(),
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testSubdomainNull(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationCommand(
            $faker->uuid(),
            $faker->name(),
            null,
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testSubdomainBlank(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationCommand(
            $faker->uuid(),
            $faker->name(),
            '',
            $faker->uuid()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testCreatedByIdNull(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->slug(),
            null
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testCreatedByIdBlank(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->slug(),
            ''
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testCreatedByIdNotUuid(): void
    {
        $faker = Factory::create();
        $command = new CreateApplicationCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->slug(),
            $faker->name()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }
}