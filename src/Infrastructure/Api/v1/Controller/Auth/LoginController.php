<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\v1\Controller\Auth;

use App\Domain\Shared\CommandBusInterface;
use App\Domain\Shared\QueryBusInterface;
use App\Infrastructure\CommandFactory\LoginCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use App\Infrastructure\QueryFactory\GetJwtQueryFactory;
use App\Infrastructure\Security\ApiKeyAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
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
        $query = GetJwtQueryFactory::fromRequest($request);
        $result = $this->queryBus->dispatch($query);
        return new JsonResponse($result->toArray(), Response::HTTP_ACCEPTED);
    }
}