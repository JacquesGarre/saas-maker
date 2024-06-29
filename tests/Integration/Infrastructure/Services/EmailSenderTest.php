<?php

namespace App\Tests\Integration\Infrastructure\Services;

use App\Infrastructure\Services\EmailSender;
use App\Infrastructure\Services\EmailTemplateRenderer;
use App\Tests\Stubs\Domain\Email\UserVerificationEmailStub;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class EmailSenderTest extends KernelTestCase
{
    private readonly EmailSender $sender;
    private readonly EmailTemplateRenderer $renderer;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->renderer = $container->get(EmailTemplateRenderer::class);
        $this->sender = $container->get(EmailSender::class);
    }

    public function testSendEmail(): void
    {
        $email = UserVerificationEmailStub::random(
            $this->sender,
            $this->renderer
        );
        $this->sender->sendEmail($email);
        self::assertEmailCount(1);
    }
}
