<?php

declare(strict_types=1);

namespace App\Domain\Application;

use Countable;
use IteratorAggregate;
use ArrayIterator;

class ApplicationUserCollection implements Countable, IteratorAggregate
{
    private array $value;

    public function __construct(array $value = [])
    {
        $this->value = $value;
    }

    public function add(ApplicationUser $item): void
    {
        if($this->contains($item)){
            return;
        }
        $this->value[] = $item;
    }

    public function remove(ApplicationUser $item): void
    {
        foreach ($this->value as $key => $existingItem) {
            if ($existingItem->user->id()->equals($item->user->id()) && $existingItem->application->id->equals($item->application->id)) {
                unset($this->value[$key]);
            }
        }
        $this->value = array_values($this->value);
    }

    public function contains(ApplicationUser $item): bool
    {
        foreach ($this->value as $existingItem) {
            if ($existingItem->user->id()->equals($item->user->id()) && $existingItem->application->id->equals($item->application->id)) {
                return true;
            }
        }
        return false;
    }

    public function filter(callable $callback): ApplicationUserCollection
    {
        return new self(array_filter($this->value, $callback));
    }

    public function first()
    {
        return $this->value[0] ?? null;
    }

    public function count(): int
    {
        return count($this->value);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->value);
    }

    public function toArray(): array
    {
        return array_map(fn($item) => $item->toArray(), $this->value);
    }
}