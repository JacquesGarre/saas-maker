<?php

namespace App\Infrastructure\Services;

use App\Domain\Email\TemplateName;
use App\Domain\Email\TemplateRendererInterface;
use Twig\Environment;

final class EmailTemplateRenderer implements TemplateRendererInterface
{

    public const TEMPLATE_EXTENSION = "html.twig";

    public function __construct(private readonly Environment $twig)
    {
    }

    public function render(TemplateName $templateName, ?array $parameters = []): string
    {
        $templatePath = sprintf(
            "%s.%s", 
            $templateName->value, 
            self::TEMPLATE_EXTENSION
        );
        return $this->twig->render($templatePath, $parameters);
    }
}