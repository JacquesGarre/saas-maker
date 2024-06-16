<?php

namespace App\Infrastructure\Services;

use App\Domain\Email\TemplateName;
use App\Domain\Email\TemplateRendererInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class EmailTemplateRenderer implements TemplateRendererInterface
{
    public const TEMPLATE_FOLDER = "src/Infrastructure/Email/Template/";
    public const TEMPLATE_EXTENSION = "html.twig";
    private readonly Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(self::TEMPLATE_FOLDER);
        $this->twig = new Environment($loader);
    }

    public function render(TemplateName $templateName, ?array $parameters = []): string
    {
        $template = sprintf(
            "%s.%s", 
            $templateName->value, 
            self::TEMPLATE_EXTENSION
        );
        return $this->twig->render($template, $parameters);
    }
}