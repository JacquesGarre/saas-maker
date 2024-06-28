<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\v1\Controller\JsonResponse;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UserCreatedJsonResponse extends JsonResponse {
    
    const MESSAGE = 'Your account has been successfully created. A verification email has been sent to your email address';

    public function __construct()
    {
        parent::__construct([
            'message' => self::MESSAGE,
            'data' => []
        ], Response::HTTP_CREATED);
    }
}