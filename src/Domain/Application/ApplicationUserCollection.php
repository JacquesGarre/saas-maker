<?php

namespace App\Domain\Application;

use Ramsey\Collection\AbstractCollection;

final class ApplicationUserCollection extends AbstractCollection
{
    public function getType(): string
    {
        return ApplicationUser::class;
    }
}