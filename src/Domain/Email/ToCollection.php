<?php

namespace App\Domain\Shared;

use App\Domain\Email\To;
use Ramsey\Collection\AbstractCollection;

final class ToCollection extends AbstractCollection
{
    public function getType(): string
    {
        return To::class;
    }
}