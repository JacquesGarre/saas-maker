<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\User;

use App\Domain\User\PasswordHash;

final class PasswordHashStub
{
    public static function random(): PasswordHash
    {
        return PasswordHash::fromPlainPassword("p@ssw0Rd");
    }
}