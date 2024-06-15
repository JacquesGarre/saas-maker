<?php

declare(strict_types=1);

namespace App\Domain\Email;

interface TemplateRendererInterface {

    public function render(TemplateName $templateName, ?array $parameters = []): string;

}