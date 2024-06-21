<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Application\Application;
use App\Domain\User\User;
use ArrayIterator;
use IteratorAggregate;

final class ApplicationUserCollection implements IteratorAggregate
{
    private array $items = [];

    public function add(ApplicationUser $applicationUser): void
    {
        $this->items[] = $applicationUser;
    }

    public function remove(ApplicationUser $applicationUser): void
    {
        foreach ($this->items as $key => $item) {
            if ($item === $applicationUser) {
                unset($this->items[$key]);
            }
        }
        $this->items = array_values($this->items);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->getIterator());
    }

    public function first(): ?ApplicationUser
    {   
        $iterator = $this->getIterator();
        return isset($iterator[0]) ? $iterator[0] : null;
    }

    public function last(): ?ApplicationUser
    {   
        $iterator = $this->getIterator();
        return isset($iterator[count($iterator)-1]) ? $iterator[count($iterator)-1] : null;
    }

    public function toArray(): array
    {
        return array_map(fn(ApplicationUser $applicationUser) => $applicationUser->toArray(), $this->items);
    }

    public function findByUser(User $user): ?ApplicationUser
    {
        foreach ($this->items as $applicationUser) {
            if ($applicationUser->user->id()->equals($user->id())) {
                return $applicationUser;
            }
        }
        return null;
    }

    // TODO : TEST THIS
    public function hasUser(User $user): bool
    {
        $applicationUser = $this->findByUser($user);
        return $applicationUser !== null;
    }

}