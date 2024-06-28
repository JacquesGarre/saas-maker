<?php

namespace App\Infrastructure\Services;

use App\Domain\Email\TemplateName;
use App\Domain\Email\UserVerificationEmail;
use App\Tests\Stubs\Domain\User\UserStub;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment;

final class EmailTemplateRendererTest extends KernelTestCase
{
    private readonly EmailTemplateRenderer $renderer;
    private readonly Environment $twig;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->renderer = $container->get(EmailTemplateRenderer::class);
        $this->twig = $container->get(Environment::class);
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
        $html = $this->renderer->render($templateName, ['user' => $user->toArray()]);
        $templatePath = UserVerificationEmail::TEMPLATE_NAME.'.'.EmailTemplateRenderer::TEMPLATE_EXTENSION;
        $expected = $this->twig->render($templatePath, ['user' => $user->toArray()]);
        self::assertEquals($this->normalize($expected), $this->normalize($html));
    }
}
