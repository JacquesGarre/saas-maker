<?php

namespace App\Domain\Email;

use App\Domain\Email\Bcc;
use Ramsey\Collection\AbstractCollection;

final class BccCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Bcc::class;
    }

    public function toArray(): array 
    {
        $iterator = $this->getIterator();
        return iterator_to_array($iterator);
    }
}