<?php

namespace Support;

use Iterator;

class Menu implements Iterator
{
    protected array $items = [];
    protected int $position = 0;

    public function add($item)
    {
        $this->items[] = $item;
    }

    public function remove($item)
    {
        $this->items = array_values(array_filter($this->items, function ($menuItem) use ($item) {
            return $menuItem !== $item;
        }));
    }

    public function all(): array
    {
        return $this->items;
    }

    public function isActive($item): bool
    {
        foreach ($this->items as $sss) {
            if ($sss === $item) {
                return true;
            }
        }

        return false;
    }


    public function hasItem($item): bool
    {
        return in_array($item, $this->items);
    }


//    public function rewind()
//    {
//        $this->position = 0;
//    }



    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function rewind(): void
    {
        // TODO: Implement rewind() method.
    }
}
