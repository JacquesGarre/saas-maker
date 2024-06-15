<?php

declare(strict_types=1);

namespace App\Domain\Email;

final class Html {

    private function __construct(public readonly string $value)
    {
        
    }

    public static function create(
        TemplateRendererInterface $renderer,
        TemplateName $templateName,
        ?array $parameters = []
    ): self {
        $html = $renderer->render($templateName, $parameters);
        return new self($html);
    }
}