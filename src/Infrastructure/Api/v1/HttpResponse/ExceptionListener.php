<?php

namespace App\Infrastructure\Api\v1\HttpResponse;

use App\Infrastructure\Security\Exception\UnauthenticatedRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = match (true) {
            $exception instanceof ValidationFailedException => self::handleValidationFailedException($exception),
            $exception instanceof UnauthenticatedRequestException => self::handleUnauthenticatedRequestException($exception),
            default => self::handleGenericException($exception),
        };

        $event->setResponse($response);
    }

    private static function handleValidationFailedException(ValidationFailedException $exception): JsonResponse
    {
        $errors = [];
        foreach ($exception->getViolations() as $violation) {
            $errors[] = $violation->getMessage();
        }
        return new JsonResponse([
            'errors' => $errors,
            'code' => $exception->getCode(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private static function handleGenericException(\Throwable $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ], Response::HTTP_BAD_REQUEST);
    }

    private static function handleUnauthenticatedRequestException(
        UnauthenticatedRequestException $exception
    ): JsonResponse {
        return new JsonResponse([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ], Response::HTTP_UNAUTHORIZED);
    }
}
