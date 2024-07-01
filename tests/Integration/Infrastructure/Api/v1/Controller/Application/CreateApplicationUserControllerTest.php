<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Api\v1\Controller\Application;

use App\Application\Auth\LoginCommand\LoginCommand;
use App\Domain\Shared\CommandBusInterface;
use App\Domain\Application\ApplicationRepositoryInterface;
use App\Domain\Shared\EmailAddress;
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


final class CreateApplicationUserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepositoryInterface $userRepository;
    private ApplicationRepositoryInterface $applicationRepository;
    private CommandBusInterface $commandBus;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $container = self::getContainer();
        $this->userRepository = $container->get(UserRepositoryInterface::class);
        $this->applicationRepository = $container->get(ApplicationRepositoryInterface::class);
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

        // Create application
        $application = ApplicationStub::random($user);
        $this->applicationRepository->add($application);

        // Send request
        $faker = Factory::create();
        $data = [
            'email' => $faker->email(),
        ];
        $this->client->request(
            'POST',
            '/api/v1/applications/'.$application->id->value->toString().'/users',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_'.JwtAuthenticator::HEADER => 'Bearer '.$user->jwt()->value
            ],
            json_encode($data, JSON_THROW_ON_ERROR)
        );
        self::assertEquals(
            Response::HTTP_CREATED,
            $this->client->getResponse()->getStatusCode()
        );

        $fetchedApplication = $this->applicationRepository->ofId($application->id);
        $fetchedUser = $this->userRepository->findOneByEmail(EmailAddress::fromString($data['email']));
        self::assertTrue($fetchedApplication->hasUser($fetchedUser));
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

        // Create application
        $application = ApplicationStub::random($user);
        $this->applicationRepository->add($application);

        // Send request
        $faker = Factory::create();
        $data = [
            'email' => $faker->email(),
        ];
        $this->client->request(
            'POST',
            '/api/v1/applications/'.$application->id->value->toString().'/users',
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
