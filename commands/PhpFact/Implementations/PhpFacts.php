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
     */
    public function __construct(StorageInterface $storage)
    {
        $this->facts = $this->fetch($storage);

        if (empty($this->facts)) {
            throw new PhpFactException('Facts array was empty.');
        }
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
     * Fetch data from storage.
     *
     * @param StorageInterface $storage
     * @return array
     */
    private function fetch(StorageInterface $storage): array
    {
        return $storage->get();
    }
}
