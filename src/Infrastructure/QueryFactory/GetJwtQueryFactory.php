<?php

declare(strict_types=1);

namespace App\Infrastructure\QueryFactory;

use App\Application\Auth\GetJwtQuery\GetJwtQuery;
use App\Infrastructure\Exception\InvalidRequestContentException;
use Symfony\Component\HttpFoundation\Request;

final class GetJwtQueryFactory
{
    /**
     * @throws InvalidRequestContentException
     */
    public static function fromRequest(Request $request): GetJwtQuery
    {
        $content = json_decode($request->getContent(), true);
        if ($content === null) {
            throw new InvalidRequestContentException("Invalid json body");
        }
        return new GetJwtQuery(
            $content['email'] ?? null
        );
    }
}