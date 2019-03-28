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
     * Returns random fact.
     *
     * @return string
     */
    public function getRandom(): string
    {
        $length = count($this->facts) - 1;

        return $this->facts[random_int(0, $length)];
    }

    /**
     * Return facts count.
     *
     * @return int
     */
    public function count()
    {
        return count($this->facts);
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
