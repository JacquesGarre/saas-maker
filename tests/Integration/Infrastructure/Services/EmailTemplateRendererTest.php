<?php

namespace App\Infrastructure\Services;

use App\Domain\Email\TemplateName;
use App\Domain\Email\UserVerificationEmail;
use App\Tests\Stubs\Domain\User\UserStub;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class EmailTemplateRendererTest extends KernelTestCase
{
    private readonly EmailTemplateRenderer $renderer;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->renderer = $container->get(EmailTemplateRenderer::class);
    }

    private function normalize($string): string
    {
        $string = trim($string);
        $string = preg_replace('/\s+/', ' ', $string);
        $string = preg_replace('/\n+/', "\n", $string);
        return $string;
    }

    public function testRender(): void
    {
        $templateName = new TemplateName(UserVerificationEmail::TEMPLATE_NAME);
        $user = UserStub::random();
        $html = $this->renderer->render($templateName, $user->toArray());
        $expected = <<<HTML
            <html>
                <body>
                    <p>Hello name,</p>
                    <p>This is a test email.</p>
                    <p>Best regards,<br/>Your Company</p>
                </body>
            </html>
        HTML;
        self::assertEquals($this->normalize($expected), $this->normalize($html));
    }
}
