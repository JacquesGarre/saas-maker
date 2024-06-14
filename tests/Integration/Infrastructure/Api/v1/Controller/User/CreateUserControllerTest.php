<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Api\v1\Controller\User;

use App\Domain\Shared\Id;
use App\Domain\User\UserRepositoryInterface;
use Faker\Factory;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


final class CreateUserControllerTest extends WebTestCase
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
        $faker = Factory::create();
        $data = [
            'id' => $faker->uuid(),
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'email' => $faker->email(),
            'password' => 'p@ssw0rD',
        ];

        $this->client->request(
            'POST',
            '/api/v1/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data, JSON_THROW_ON_ERROR)
        );

        self::assertEquals(
            Response::HTTP_CREATED,
            $this->client->getResponse()->getStatusCode()
        );

        $fetchedUser = $this->repository->ofId(new Id($data['id']));
        self::assertNotNull($fetchedUser);
    }

    public function testBadRequest(): void
    {
        $this->client->request(
            'POST',
            '/api/v1/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([], JSON_THROW_ON_ERROR)
        );

        self::assertEquals(
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getStatusCode()
        );
    }
}