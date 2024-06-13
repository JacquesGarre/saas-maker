<?php

namespace App\Infrastructure\Api\v1\HttpResponse;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionListener
{

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $response = match (true) {
            $exception instanceof ValidationFailedException => $this->handleValidationFailedException($exception),
            $exception instanceof HttpExceptionInterface => $this->handleHttpException($exception),
            default => $this->handleGenericException($exception),
        };

        $event->setResponse($response);
    }

    private function handleValidationFailedException(ValidationFailedException $exception): JsonResponse
    {
        $errors = [];
        foreach ($exception->getViolations() as $violation) {
            $errors[] = $violation->getMessage();
        }
        return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function handleHttpException(HttpExceptionInterface $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
            'code' => $exception->getStatusCode(),
        ], JsonResponse::HTTP_BAD_REQUEST);
    }

    private function handleGenericException(\Throwable $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
