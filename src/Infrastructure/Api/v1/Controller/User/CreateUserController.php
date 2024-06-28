<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\v1\Controller\User;

use App\Domain\Shared\CommandBusInterface;
use App\Infrastructure\CommandFactory\CreateUserCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use App\Infrastructure\Security\ApiKeyAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Infrastructure\Api\v1\Controller\JsonResponse\UserCreatedJsonResponse;

final class CreateUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly ApiKeyAuthenticator $apiKeyAuthenticator
    ) {
    }

    /**
     * @throws InvalidRequestContentException
     */
    #[Route('/api/v1/users', name: 'create_user', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $this->apiKeyAuthenticator->authenticate($request);
        $command = CreateUserCommandFactory::fromRequest($request);
        $this->commandBus->dispatch($command);
        return new UserCreatedJsonResponse();
    }
}