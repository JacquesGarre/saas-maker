<?php

declare(strict_types=1);

namespace App\Application\Auth\GetJwtQuery;

use App\Domain\Shared\QueryResultInterface;

final class GetJwtQueryResult implements QueryResultInterface {

    public function __construct(public readonly ?string $jwt = null)
    {
        
    }

    public function toArray(): array 
    {
        return [
            'jwt' => $this->jwt
        ];
    }

}