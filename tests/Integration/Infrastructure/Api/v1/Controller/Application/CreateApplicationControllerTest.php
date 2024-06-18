<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Api\v1\Controller\Application;

use App\Application\Auth\LoginCommand\LoginCommand;
use App\Domain\Shared\CommandBusInterface;
use App\Domain\Application\ApplicationRepositoryInterface;
use App\Domain\Shared\Id;
use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\Security\JwtAuthenticator;
use App\Tests\Stubs\Domain\Application\ApplicationStub;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\BrowserKit\Cookie;


final class CreateApplicationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ApplicationRepositoryInterface $repository;
    private UserRepositoryInterface $userRepository;
    private CommandBusInterface $commandBus;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $container = self::getContainer();
        $this->repository = $container->get(ApplicationRepositoryInterface::class);
        $this->userRepository = $container->get(UserRepositoryInterface::class);
        $this->commandBus = $container->get(CommandBusInterface::class);
    }

    /**
     * @throws JsonException
     */
    public function testSunnyCase(): void
    {   
        // Init user and log in
        $password = 'p@ssw0rD';
        $user = UserStub::randomVerified($password);
        $this->userRepository->add($user);
        $command = new LoginCommand(
            $user->email()->value,
            $password
        );
        $this->commandBus->dispatch($command);
        $user = $this->userRepository->ofId($user->id());

        $faker = Factory::create();
        $data = [
            'id' => $faker->uuid(),
            'name' => $faker->name(),
            'subdomain' => $faker->slug()
        ];
        $this->client->getCookieJar()->set(new Cookie(JwtAuthenticator::JWT_COOKIE, $user->jwt()->value));
        $this->client->request(
            'POST',
            '/api/v1/applications',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data, JSON_THROW_ON_ERROR)
        );
        self::assertEquals(
            Response::HTTP_CREATED,
            $this->client->getResponse()->getStatusCode()
        );

        $applicationId = new Id($data['id']);
        $fetchedApplication = $this->repository->ofId($applicationId);
        self::assertEquals($data['id'], $fetchedApplication->id->value->toString());
        self::assertEquals($data['name'], $fetchedApplication->name->value);
        self::assertEquals($data['subdomain'], $fetchedApplication->subdomain->value);
        self::assertEquals($user->id()->value->toString(), $fetchedApplication->createdBy->id()->value->toString());
    }

    public function testUnauthenticated(): void
    {
        // Init user and log in
        $password = 'p@ssw0rD';
        $user = UserStub::randomVerified($password);
        $this->userRepository->add($user);
        $command = new LoginCommand(
            $user->email()->value,
            $password
        );
        $this->commandBus->dispatch($command);
        $user = $this->userRepository->ofId($user->id());

        $faker = Factory::create();
        $data = [
            'id' => $faker->uuid(),
            'name' => $faker->name(),
            'subdomain' => $faker->slug()
        ];
        $this->client->request(
            'POST',
            '/api/v1/applications',
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
