<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\User\SendVerificationEmailOnUserCreatedEvent;

use App\Application\User\SendEmailOnVerificationTokenGeneratedEvent\SendEmailOnVerificationTokenGeneratedEventHandler;
use App\Domain\Auth\JwtGeneratorInterface;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\Email\EmailSenderInterface;
use App\Domain\Email\TemplateRendererInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\Shared\EventBusInterface;
use App\Domain\Shared\TokenGeneratorInterface;
use App\Tests\Stubs\Domain\User\UserStub;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SendEmailOnVerificationTokenGeneratedEventHandlerTest extends KernelTestCase
{
    private readonly TemplateRendererInterface $templateRenderer;
    private readonly EmailSenderInterface $emailSender;
    private readonly UserRepositoryInterface $repository;
    private $eventBus;
    private readonly SendEmailOnVerificationTokenGeneratedEventHandler $handler;
    private readonly TokenGeneratorInterface $tokenGenerator;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->repository = $container->get(UserRepositoryInterface::class);
        $this->eventBus = $this->createMock(EventBusInterface::class);
        $this->templateRenderer = $container->get(TemplateRendererInterface::class);
        $this->emailSender = $container->get(EmailSenderInterface::class);
        $this->handler = new SendEmailOnVerificationTokenGeneratedEventHandler(
            $this->repository,
            $this->templateRenderer,
            $this->emailSender,
            $this->eventBus,
            getenv('EMAIL_DEFAULT_SENDER')
        );
        $this->tokenGenerator = $container->get(TokenGeneratorInterface::class);
    }

    public function testSunnyCase(): void
    {   
        $user = UserStub::random();
        $user->generateVerificationToken($this->tokenGenerator);
        $this->repository->add($user);
        $domainEvent = $user->domainEvents->last();
        $this->eventBus->expects($this->once())->method('notifyAll');
        ($this->handler)($domainEvent);
        self::assertEmailCount(1);
    }

    public function testUserNotFoundException(): void
    {   
        $user = UserStub::random();
        $user->generateVerificationToken($this->tokenGenerator);
        $domainEvent = $user->domainEvents->last();
        $this->expectException(UserNotFoundException::class);
        ($this->handler)($domainEvent);
    }

    public function testUserAlreadyVerified(): void
    {   
        $user = UserStub::random();
        $user->generateVerificationToken($this->tokenGenerator);
        $domainEvent = $user->domainEvents->last();
        $user->verify();
        $this->repository->add($user);
        $this->eventBus->expects($this->never())->method('notifyAll');
        ($this->handler)($domainEvent);
        self::assertEmailCount(0);
    }
}