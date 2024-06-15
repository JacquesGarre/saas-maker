<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\v1\Controller\User;

use App\Domain\Shared\CommandBusInterface;
use App\Infrastructure\CommandFactory\UpdateUserCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use App\Infrastructure\Security\JwtAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly JwtAuthenticator $jwtAuthenticator
    ) {
    }

    /**
     * @throws InvalidRequestContentException
     */
    #[Route('/api/v1/users/{uuid}', name: 'update_user', methods: ['PUT', 'PATCH'])]
    public function __invoke(Request $request): JsonResponse
    {
        $user = $this->jwtAuthenticator->authenticate($request);
        $command = UpdateUserCommandFactory::fromRequestAndUser($request, $user);
        $this->commandBus->dispatch($command);
        return new JsonResponse([], Response::HTTP_ACCEPTED);
    }
}