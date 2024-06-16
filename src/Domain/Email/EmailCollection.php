<?php

namespace App\Domain\Email;

use App\Domain\Shared\AbstractEmail;
use Ramsey\Collection\AbstractCollection;

final class EmailCollection extends AbstractCollection
{
    public function getType(): string
    {
        return AbstractEmail::class;
    }

    public function toArray(): array 
    {
        $emails = [];
        foreach ($this->getIterator() as $email) {
            $emails[] = $email->value;
        }
        return $emails;
    }
}