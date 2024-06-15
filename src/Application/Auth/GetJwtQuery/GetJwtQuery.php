<?php

declare(strict_types=1);

namespace App\Application\Auth\GetJwtQuery;

use App\Domain\Shared\QueryInterface;

final class GetJwtQuery implements QueryInterface {

    public function __construct(public readonly ?string $email = null)
    {
    }
}