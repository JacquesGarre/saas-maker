<?php

namespace App\Domain\Email;

use App\Domain\Email\Cc;
use Ramsey\Collection\AbstractCollection;

final class CcCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Cc::class;
    }

    public function toArray(): array 
    {
        $iterator = $this->getIterator();
        return iterator_to_array($iterator);
    }
}