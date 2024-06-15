<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\v1\Controller\User;

use App\Domain\Shared\CommandBusInterface;
use App\Infrastructure\CommandFactory\UpdateUserCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use App\Infrastructure\Security\ApiKeyAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly ApiKeyAuthenticator $apiKeyAuthenticator
    ) {
    }

    /**
     * @throws InvalidRequestContentException
     */
    #[Route('/api/v1/users/{uuid}', name: 'update_user', methods: ['PUT', 'PATCH'])]
    public function __invoke(Request $request): JsonResponse
    {
        dd('jwt');
        $this->apiKeyAuthenticator->authenticate($request); // TODO : JWT!
        $updateUserCommand = UpdateUserCommandFactory::fromRequest($request);
        $this->commandBus->dispatch($updateUserCommand);
        return new JsonResponse([], Response::HTTP_ACCEPTED);
    }
}