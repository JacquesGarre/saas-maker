<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Api\v1\Controller\User;

use App\Application\Auth\LoginCommand\LoginCommand;
use App\Domain\Shared\CommandBusInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\Security\JwtAuthenticator;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\BrowserKit\Cookie;


final class UpdateUserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepositoryInterface $repository;
    private CommandBusInterface $commandBus;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $container = self::getContainer();
        $this->repository = $container->get(UserRepositoryInterface::class);
        $this->commandBus = $container->get(CommandBusInterface::class);
    }

    /**
     * @throws JsonException
     */
    public function testSunnyCase(): void
    {
        $password = 'p@ssw0rD';
        $user = UserStub::randomVerified($password);
        $this->repository->add($user);
        $command = new LoginCommand(
            $user->email()->value,
            $password
        );
        $this->commandBus->dispatch($command);
        $user = $this->repository->ofId($user->id);
        $faker = Factory::create();
        $data = [
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'email' => $faker->email(),
            'password' => 'p@ssw000rD',
        ];

        $this->client->getCookieJar()->set(new Cookie(JwtAuthenticator::JWT_COOKIE, $user->jwt()->value));
        $this->client->request(
            'PUT',
            '/api/v1/users/' . $user->id->value->toString(),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data, JSON_THROW_ON_ERROR)
        );
        self::assertEquals(
            Response::HTTP_ACCEPTED,
            $this->client->getResponse()->getStatusCode()
        );

        $fetchedUser = $this->repository->ofId($user->id);
        self::assertEquals($data['first_name'], $fetchedUser->firstName()->value);
        self::assertEquals($data['last_name'], $fetchedUser->lastName()->value);
        self::assertEquals($data['email'], $fetchedUser->email()->value);
        self::assertTrue($fetchedUser->passwordHash()->matches($data['password']));
    }

    public function testUnauthenticated(): void
    {
        $user = UserStub::random();
        $this->repository->add($user);
        $faker = Factory::create();
        $data = [
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'email' => $faker->email(),
            'password' => 'p@ssw0rD',
        ];
        $this->client->request(
            'PUT',
            '/api/v1/users/' . $user->id->value->toString(),
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
