<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Validator;

use App\Application\User\CreateUserCommand\CreateUserCommand;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function PHPSTORM_META\map;

final class CreateUserCommandValidatorTest extends KernelTestCase {

/*        id:
            - NotBlank: ~
            - Type: string
        firstName:
            - NotBlank: ~
            - Type: string
        lastName:
            - NotBlank: ~
            - Type: string
        email:
            - NotBlank: ~
            - Email: ~
        password:
            - NotBlank: ~
            - Type: string
            */

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
        $command = new CreateUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->name(),
            $faker->email(),
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(0, $errors);
    }

    public function testIdNull(): void
    {
        $faker = Factory::create();
        $command = new CreateUserCommand(
            null,
            $faker->name(),
            $faker->name(),
            $faker->email(),
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testIdBlank(): void
    {
        $faker = Factory::create();
        $command = new CreateUserCommand(
            '',
            $faker->name(),
            $faker->name(),
            $faker->email(),
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testIdNotUuid(): void
    {
        $faker = Factory::create();
        $command = new CreateUserCommand(
            $faker->name(),
            $faker->name(),
            $faker->name(),
            $faker->email(),
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testFirstNameNull(): void
    {
        $faker = Factory::create();
        $command = new CreateUserCommand(
            $faker->uuid(),
            null,
            $faker->name(),
            $faker->email(),
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testFirstNameBlank(): void
    {
        $faker = Factory::create();
        $command = new CreateUserCommand(
            $faker->uuid(),
            '',
            $faker->name(),
            $faker->email(),
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testLastNameNull(): void
    {
        $faker = Factory::create();
        $command = new CreateUserCommand(
            $faker->uuid(),
            $faker->name(),
            null,
            $faker->email(),
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testLastNameBlank(): void
    {
        $faker = Factory::create();
        $command = new CreateUserCommand(
            $faker->uuid(),
            $faker->name(),
            null,
            $faker->email(),
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testEmailNull(): void
    {
        $faker = Factory::create();
        $command = new CreateUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->name(),
            null,
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testEmailBlank(): void
    {
        $faker = Factory::create();
        $command = new CreateUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->name(),
            '',
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testInvalidEmail(): void
    {
        $faker = Factory::create();
        $command = new CreateUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->name(),
            $faker->name(),
            $faker->password()
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testPasswordNull(): void
    {
        $faker = Factory::create();
        $command = new CreateUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->name(),
            $faker->email(),
            null,
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }

    public function testPasswordBlank(): void
    {
        $faker = Factory::create();
        $command = new CreateUserCommand(
            $faker->uuid(),
            $faker->name(),
            $faker->name(),
            $faker->email(),
            '',
        );
        $errors = $this->validator->validate($command);
        self::assertCount(1, $errors);
    }
}