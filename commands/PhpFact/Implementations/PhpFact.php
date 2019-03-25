<?php

namespace SoerBot\Commands\PhpFact\Implementations;

use SoerBot\Commands\PhpFact\Exceptions\PhpFactStorageException;

class PhpFact
{
    /**
     * @var array
     */
    private $facts = [];

    /**
     * PhpFact constructor
     *
     * @param array $facts
     * @throws PhpFactStorageException
     */
    public function __construct()
    {
        try {
            $storage = new Storage();
        } catch (\Throwable $e) {
            throw new PhpFactStorageException();
        }

        $this->facts = $storage->fetch();
    }

    /**
     * Returns random fact
     *
     * @return string
     */
    public function get(): string
    {
        $length = count($this->facts) - 1;

        return $this->facts[rand(0, $length)];
    }
}