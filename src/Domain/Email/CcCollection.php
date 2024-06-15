<?php

namespace App\Domain\Shared;

use App\Domain\Email\Cc;
use Ramsey\Collection\AbstractCollection;

final class CcCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Cc::class;
    }
}