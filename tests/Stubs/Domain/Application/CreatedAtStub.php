<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\Application;

use App\Domain\Application\CreatedAt;
use DateTime;

final class CreatedAtStub {

    public static function random(): CreatedAt
    {
        $value = (new DateTime())->format(DateTime::ATOM);
        return CreatedAt::fromString($value);
    }
}