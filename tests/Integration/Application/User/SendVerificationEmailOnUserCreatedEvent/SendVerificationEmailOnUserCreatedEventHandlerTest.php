<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\User\SendVerificationEmailOnUserCreatedEvent;

use App\Application\User\Exception\UserNotFoundException;
use App\Application\User\SendVerificationEmailOnUserCreatedEvent\SendVerificationEmailOnUserCreatedEventHandler;
use App\Domain\Email\EmailSenderInterface;
use App\Domain\Email\TemplateRendererInterface;
use App\Domain\Shared\CommandBusInterface;
use App\Domain\Shared\EmailAddress;
use App\Domain\User\PasswordHash;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\Shared\EventBusInterface;
use App\Domain\User\FirstName;
use App\Domain\User\LastName;
use App\Domain\User\User;
use App\Tests\Stubs\Domain\Shared\IdStub;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SendVerificationEmailOnUserCreatedEventHandlerTest extends KernelTestCase
{
    private readonly TemplateRendererInterface $templateRenderer;
    private readonly EmailSenderInterface $emailSender;
    private readonly UserRepositoryInterface $repository;
    private $eventBus;
    private readonly SendVerificationEmailOnUserCreatedEventHandler $handler;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->repository = $container->get(UserRepositoryInterface::class);
        $this->eventBus = $this->createMock(EventBusInterface::class);
        $this->templateRenderer = $container->get(TemplateRendererInterface::class);
        $this->emailSender = $container->get(EmailSenderInterface::class);
        $this->handler = new SendVerificationEmailOnUserCreatedEventHandler(
            $this->repository,
            $this->templateRenderer,
            $this->emailSender,
            $this->eventBus,
            getenv('EMAIL_DEFAULT_SENDER')
        );
    }

    public function testSunnyCase(): void
    {   
        $faker = Factory::create();
        $user = User::create(
            IdStub::random(),
            new FirstName($faker->firstName()),
            new LastName($faker->lastName()),
            EmailAddress::fromString($faker->email()),
            PasswordHash::fromPlainPassword('p@ssw0RD')
        );
        $this->repository->add($user);
        $domainEvent = $user->domainEvents->last();
        $this->eventBus->expects($this->once())->method('notifyAll');
        ($this->handler)($domainEvent);
        self::assertEmailCount(1);
    }

    public function testUserNotFoundException(): void
    {   
        $faker = Factory::create();
        $user = User::create(
            IdStub::random(),
            new FirstName($faker->firstName()),
            new LastName($faker->lastName()),
            EmailAddress::fromString($faker->email()),
            PasswordHash::fromPlainPassword('p@ssw0RD')
        );
        $domainEvent = $user->domainEvents->last();
        $this->expectException(UserNotFoundException::class);
        ($this->handler)($domainEvent);
    }

    public function testUserAlreadyVerified(): void
    {   
        $faker = Factory::create();
        $user = User::create(
            IdStub::random(),
            new FirstName($faker->firstName()),
            new LastName($faker->lastName()),
            EmailAddress::fromString($faker->email()),
            PasswordHash::fromPlainPassword('p@ssw0RD')
        );
        $domainEvent = $user->domainEvents->last();
        $user->verify();
        $this->repository->add($user);
        ($this->handler)($domainEvent);
        $this->eventBus->expects($this->never())->method('notifyAll');
        ($this->handler)($domainEvent);
        self::assertEmailCount(0);
    }
}