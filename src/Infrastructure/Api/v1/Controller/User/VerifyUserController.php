<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\v1\Controller\User;

use App\Domain\Shared\CommandBusInterface;
use App\Infrastructure\CommandFactory\VerifyUserCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use App\Infrastructure\Security\ApiKeyAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VerifyUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly ApiKeyAuthenticator $apiKeyAuthenticator
    ) {
    }

    /**
     * @throws InvalidRequestContentException
     */
    #[Route('/api/v1/users/verify', name: 'verify_user', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {   
        $this->apiKeyAuthenticator->authenticate($request);
        $command = VerifyUserCommandFactory::fromRequest($request);
        $this->commandBus->dispatch($command);
        return new JsonResponse([], Response::HTTP_ACCEPTED);
    }
}