<?php

namespace App\Domain\Email;

use App\Domain\Shared\EmailAddress;
use Ramsey\Collection\AbstractCollection;

final class EmailAddressCollection extends AbstractCollection
{
    public function getType(): string
    {
        return EmailAddress::class;
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