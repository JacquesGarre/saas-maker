<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Email;

use App\Domain\Email\Html;
use App\Domain\Email\TemplateName;
use App\Domain\Email\TemplateRendererInterface;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class HtmlTest extends TestCase {

    public function testCreate(): void {
        $randomHtml = Factory::create()->randomHtml();
        $renderer = $this->createMock(TemplateRendererInterface::class);
        $renderer->method('render')->willReturn($randomHtml);
        $templateName = new TemplateName("user_verification_email");
        $parameters = UserStub::random()->toArray();
        $html = Html::create(
            $renderer,
            $templateName,
            $parameters
        );
        self::assertEquals($randomHtml, $html->value);
    }

    public function testToText(): void
    {
        $randomHtml = Factory::create()->randomHtml();
        $renderer = $this->createMock(TemplateRendererInterface::class);
        $renderer->method('render')->willReturn($randomHtml);
        $templateName = new TemplateName("user_verification_email");
        $parameters = UserStub::random()->toArray();
        $html = Html::create(
            $renderer,
            $templateName,
            $parameters
        );
        $expectedText = strip_tags($randomHtml);
        self::assertEquals($expectedText, $html->toText());
    }
}