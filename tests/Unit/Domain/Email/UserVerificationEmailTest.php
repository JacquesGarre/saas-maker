<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Email;

use App\Domain\Email\EmailSenderInterface;
use App\Domain\Email\EmailSentDomainEvent;
use App\Domain\Email\TemplateRendererInterface;
use App\Domain\Email\UserVerificationEmail;
use App\Domain\Shared\EmailAddress;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class UserVerificationEmailTest extends TestCase {

    public function testFromUser(): void {  
        $html = Factory::create()->randomHtml();
        $sender = $this->createMock(EmailSenderInterface::class);
        $renderer = $this->createMock(TemplateRendererInterface::class);
        $renderer->method('render')->willReturn($html);
        $user = UserStub::random();
        $from = EmailAddress::fromString(Factory::create()->email());
        $email = UserVerificationEmail::fromUser(
            $sender,
            $renderer,
            $from,
            $user
        );
        self::assertNotNull($email->id->value);
        self::assertEquals($sender, $email->sender);
        self::assertEquals($from, $email->from);
        self::assertCount(1, $email->toCollection);
        self::assertEquals("Welcome ".$user->fullName()."! Please verify your email address", $email->subject->value);
        self::assertEquals($html, $email->html->value);
        self::assertNull($email->ccCollection);
        self::assertNull($email->bccCollection);
    }

    public function testToArray(): void {  
        $html = Factory::create()->randomHtml();
        $sender = $this->createMock(EmailSenderInterface::class);
        $renderer = $this->createMock(TemplateRendererInterface::class);
        $renderer->method('render')->willReturn($html);
        $user = UserStub::random();
        $from = EmailAddress::fromString(Factory::create()->email());
        $email = UserVerificationEmail::fromUser(
            $sender,
            $renderer,
            $from,
            $user
        );
        $expected = [
            'from' => $email->from->value,
            'to' => $email->toCollection->toArray(),
            'subject' => $email->subject->value,
            'html' => $email->html->value,
            'cc' => $email->ccCollection?->toArray(),
            'bcc' => $email->bccCollection?->toArray()
        ];
        self::assertEquals($expected, $email->toArray());
    }

    public function testSend(): void {  
        $html = Factory::create()->randomHtml();
        $sender = $this->createMock(EmailSenderInterface::class);
        $renderer = $this->createMock(TemplateRendererInterface::class);
        $renderer->method('render')->willReturn($html);
        $user = UserStub::random();
        $from = EmailAddress::fromString(Factory::create()->email());
        $email = UserVerificationEmail::fromUser(
            $sender,
            $renderer,
            $from,
            $user
        );
        $sender->expects($this->once())->method('sendEmail');
        $email->send();
        self::assertCount(1, $email->domainEvents);
        self::assertInstanceOf(EmailSentDomainEvent::class, $email->domainEvents->last());
    }
}