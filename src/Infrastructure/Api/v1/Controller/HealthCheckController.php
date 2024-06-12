<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\v1\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class HealthCheckController extends AbstractController
{
    #[Route('/api/v1/health-check', name: 'health_check')]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse();
    }
}