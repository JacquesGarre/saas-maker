<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Api\v1\Controller\User;

use App\Domain\Shared\CommandBusInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\Security\ApiKeyAuthenticator;
use App\Tests\Stubs\Domain\User\UserStub;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


final class VerifyUserControllerTest extends WebTestCase
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
        $user = UserStub::random();
        $this->repository->add($user);
        self::assertFalse($user->isVerified()->value);
        $this->client->request(
            'POST',
            '/api/v1/users/' . $user->id()->value->toString().'/verify',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_'.ApiKeyAuthenticator::API_KEY_HEADER => getenv('API_KEY')
            ]
        );
        self::assertEquals(
            Response::HTTP_ACCEPTED,
            $this->client->getResponse()->getStatusCode()
        );
        $fetchedUser = $this->repository->ofId($user->id());
        self::assertTrue($fetchedUser->isVerified()->value);
    }

    public function testUnauthenticated(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);
        self::assertFalse($user->isVerified()->value);
        $this->client->request(
            'POST',
            '/api/v1/users/' . $user->id()->value->toString().'/verify',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ]
        );
        self::assertEquals(
            Response::HTTP_UNAUTHORIZED,
            $this->client->getResponse()->getStatusCode()
        );
    }
}
