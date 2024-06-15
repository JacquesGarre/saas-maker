<?php

namespace App\Domain\Shared;

use App\Domain\Email\Bcc;
use Ramsey\Collection\AbstractCollection;

final class BccCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Bcc::class;
    }
}