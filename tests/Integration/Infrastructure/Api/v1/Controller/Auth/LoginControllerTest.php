<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Api\v1\Controller\Auth;

use App\Domain\Shared\Id;
use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\Security\ApiKeyAuthenticator;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


final class LoginControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $container = self::getContainer();
        $this->repository = $container->get(UserRepositoryInterface::class);
    }

    /**
     * @throws JsonException
     */
    public function testSunnyCase(): void
    {   
        $password = 'p@ssw0rD';
        $user = UserStub::randomVerified($password);
        $this->repository->add($user);

        $data = [
            'email' => $user->email->value,
            'password' => $password,
        ];
        $this->client->request(
            'POST',
            '/api/v1/login',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_'.ApiKeyAuthenticator::API_KEY_HEADER => getenv('API_KEY')
            ],
            json_encode($data, JSON_THROW_ON_ERROR)
        );
        self::assertEquals(
            Response::HTTP_ACCEPTED,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testBadRequest(): void
    {
        $this->client->request(
            'POST',
            '/api/v1/login',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_'.ApiKeyAuthenticator::API_KEY_HEADER => getenv('API_KEY')
            ],
            json_encode([], JSON_THROW_ON_ERROR)
        );

        self::assertEquals(
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testUnauthenticated(): void
    {
        $password = 'p@ssw0rD';
        $user = UserStub::randomVerified($password);
        $this->repository->add($user);

        $data = [
            'email' => $user->email->value,
            'password' => $password,
        ];
        $this->client->request(
            'POST',
            '/api/v1/login',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data, JSON_THROW_ON_ERROR)
        );
        self::assertEquals(
            Response::HTTP_UNAUTHORIZED,
            $this->client->getResponse()->getStatusCode()
        );
    }
}