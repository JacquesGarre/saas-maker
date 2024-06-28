<?php

declare(strict_types=1);

namespace App\Infrastructure\Twig;

use Twig\Extension\AbstractExtension;

class TwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly string $companyName, 
        private readonly string $frontEndUrl
    ) {
    }

    public function getGlobals(): array
    {
        return [
            'company_name' => $this->companyName,
            'frontend_url' => $this->supportEmail,
        ];
    }
}