<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\v1\Controller\User;

use App\Domain\Shared\CommandBusInterface;
use App\Infrastructure\CommandFactory\CreateUserCommandFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    /**
     * @throws InvalidRequestContentException
     */
    #[Route('/api/v1/users', name: 'create_user', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        // TODO : Authentication
        $createUserCommand = CreateUserCommandFactory::fromRequest($request);
        $this->commandBus->dispatch($createUserCommand);
        return new JsonResponse([], Response::HTTP_CREATED);
    }
}