<?php

namespace App\Infrastructure\Service;

use App\Domain\Email\TemplateName;
use App\Domain\Email\TemplateRendererInterface;
use Twig\Environment;

final class EmailTemplateRenderer implements TemplateRendererInterface
{
    public const TEMPLATE_FOLDER = "Infrastructure/Email/Template/";

    public function __construct(public readonly Environment $twig)
    {
    }

    public function render(TemplateName $templateName, ?array $parameters = []): string
    {
        $template = self::TEMPLATE_FOLDER.$templateName.'.html.twig';
        return $this->twig->render($template, $parameters);
    }
}