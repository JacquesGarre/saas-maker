<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\v1\Controller\Auth;

use App\Domain\Shared\CommandBusInterface;
use App\Infrastructure\CommandFactory\LoginCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use App\Infrastructure\Security\ApiKeyAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly ApiKeyAuthenticator $apiKeyAuthenticator
    ) {
    }

    /**
     * @throws InvalidRequestContentException
     */
    #[Route('/api/v1/login', name: 'login', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $this->apiKeyAuthenticator->authenticate($request);
        $command = LoginCommandFactory::fromRequest($request);
        $this->commandBus->dispatch($command);
        return new JsonResponse([], Response::HTTP_CREATED);
    }
}