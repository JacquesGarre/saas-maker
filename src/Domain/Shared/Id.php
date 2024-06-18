<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Domain\Shared\Exception\InvalidIdException;

final class Id {

    public UuidInterface $value;

    public function __construct(?string $uuid = null)
    {
        if ($uuid) {
            self::assertValid($uuid);
        }
        $this->value = $uuid ? Uuid::fromString($uuid) : Uuid::uuid4();
    }

    public static function assertValid(string $uuid) 
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidIdException("$uuid is not a valid uuid");
        }
    }

    public function equals(?self $id = null): bool
    {
        if (!$id) {
            return false;
        }
        return $this->value->equals($id->value);
    }
}