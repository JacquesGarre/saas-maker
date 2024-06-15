<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\QueryFactory;

use App\Application\Auth\GetJwtQuery\GetJwtQuery;
use App\Infrastructure\QueryFactory\GetJwtQueryFactory;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetJwtQueryFactoryTest extends TestCase
{

    public function testFromRequestWithValidData(): void
    {
        $faker = Factory::create();
        $requestData = [
            'email' => $faker->email()
        ];
        $request = new Request([], [], [], [], [], [], json_encode($requestData));
        $query = GetJwtQueryFactory::fromRequest($request);
        $this->assertInstanceOf(GetJwtQuery::class, $query);
        $this->assertSame($requestData['email'], $query->email);
    }

    public function testFromRequestWithInvalidData(): void
    {
        $invalidJson = '{"email": "blablabla@blabla.com",'; 
        $request = new Request([], [], [], [], [], [], $invalidJson);
        $this->expectException(InvalidRequestContentException::class);
        $this->expectExceptionMessage("Invalid json body");
        GetJwtQueryFactory::fromRequest($request);
    }
}