<?php

namespace App\Domain\Email;

use App\Domain\Email\To;
use Ramsey\Collection\AbstractCollection;

final class ToCollection extends AbstractCollection
{
    public function getType(): string
    {
        return To::class;
    }

    public function toArray(): array 
    {
        $iterator = $this->getIterator();
        return iterator_to_array($iterator);
    }
}