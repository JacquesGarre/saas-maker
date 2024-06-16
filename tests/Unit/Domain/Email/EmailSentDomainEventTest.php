<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Email;

use App\Domain\Email\EmailSenderInterface;
use App\Domain\Email\EmailSentDomainEvent;
use App\Domain\Email\TemplateRendererInterface;
use App\Tests\Stubs\Domain\Email\UserVerificationEmailStub;
use DateTime;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

final class EmailSentDomainEventTest extends TestCase
{
    public function testFromUser(): void
    {
        $email = UserVerificationEmailStub::random(
            $this->createMock(EmailSenderInterface::class),
            $this->createMock(TemplateRendererInterface::class),
        );
        $domainEvent = EmailSentDomainEvent::fromEmail($email);
        $now = (new DateTimeImmutable())->getTimestamp();
        self::assertNotNull($domainEvent->id);
        self::assertEquals(EmailSentDomainEvent::EVENT_TYPE, $domainEvent->type);
        self::assertEqualsWithDelta($now, $domainEvent->occuredAt->getTimestamp(), 1);
        self::assertEquals($email->toArray(), $domainEvent->data);
    }

    public function testOccuredAt(): void
    {
        $email = UserVerificationEmailStub::random(
            $this->createMock(EmailSenderInterface::class),
            $this->createMock(TemplateRendererInterface::class),
        );
        $domainEvent = EmailSentDomainEvent::fromEmail($email);
        self::assertEquals($domainEvent->occuredAt->format(DateTime::ATOM), $domainEvent->occuredAt());
    }
}