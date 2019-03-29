<?php

namespace SoerBot\Commands\PhpFact\Implementations;

use SoerBot\Commands\PhpFact\Exceptions\PhpFactException;
use SoerBot\Commands\PhpFact\Abstractions\StorageInterface;

class PhpFacts
{
    /**
     * @var array
     */
    private $facts = [];

    /**
     * PhpFact constructor.
     *
     * @param StorageInterface $storage
     * @throws PhpFactException
     */
    public function __construct(StorageInterface $storage)
    {
        $this->facts = $this->load($storage);

        if (empty($this->facts)) {
            throw new PhpFactException('Facts array was empty.');
        }
    }

    /**
     * @param int $position
     *
     * @return bool|string
     */
    public function get(int $position)
    {
        // position normalization for array indexes
        --$position;
        if ($this->hasPosition($position)) {
            return $this->facts[$position];
        }

        return false;
    }

    /**
     * Returns random fact.
     *
     * @return string
     */
    public function getRandom(): string
    {
        $length = count($this->facts) - 1;

        return $this->facts[rand(0, $length)];
    }

    /**
     * Return facts count.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->facts);
    }

    /**
     * @param int $position
     * @return bool
     */
    private function hasPosition(int $position): bool
    {
        $final = count($this->facts) - 1;
        if ($position < 0 || $position > $final) {
            return false;
        }

        return true;
    }

    /**
     * Fetch data from storage.
     *
     * @param StorageInterface $storage
     * @return array
     */
    private function load(StorageInterface $storage): array
    {
        return $storage->get();
    }
}
