<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Auth\GetJwtQuery;

use App\Application\Auth\GetJwtQuery\GetJwtQueryResult;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class GetJwtQueryResultTest extends TestCase
{
    public function testConstructor(): void
    {
        $jwt = Factory::create()->word();
        $queryResult = new GetJwtQueryResult($jwt);
        self::assertEquals($jwt, $queryResult->jwt);

        $queryResult = new GetJwtQueryResult();
        self::assertNull($queryResult->jwt);
    } 

    public function testToArray(): void
    {
        $jwt = Factory::create()->word();
        $queryResult = new GetJwtQueryResult($jwt);
        self::assertEquals(['jwt' => $jwt], $queryResult->toArray());

        $queryResult = new GetJwtQueryResult();
        self::assertEquals(['jwt' => null], $queryResult->toArray());
    } 
}