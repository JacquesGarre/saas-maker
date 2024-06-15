<?php

declare(strict_types=1);

namespace App\Infrastructure\Buses;

use App\Infrastructure\Buses\Exception\InvalidMessageException;
use Exception;
use JsonException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class MessageValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $errors = $this->validator->validate($envelope->getMessage());
        if (count($errors) > 0) {
            throw new InvalidMessageException($this->toJson($errors));
        }
        return $stack->next()->handle($envelope, $stack);
    }

    /**
     * @throws JsonException
     */
    private function toJson(ConstraintViolationListInterface $errors): string
    {
        $json = [];
        foreach ($errors as $error) {
            $json[$error->getPropertyPath()] = $error->getMessage();
        }
        return json_encode($json, JSON_THROW_ON_ERROR);
    }
}