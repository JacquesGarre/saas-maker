<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\v1\Controller\User;

use App\Infrastructure\CommandFactory\CreateUserCommandFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateUserController extends AbstractController
{
    public function __construct(private readonly CreateUserCommandFactory $commandFactory)
    { 
    }

    #[Route('/api/v1/users', name: 'create_user', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $createUserCommand = $this->commandFactory->fromRequest($request);
        return new JsonResponse([], Response::HTTP_CREATED);
    }
}