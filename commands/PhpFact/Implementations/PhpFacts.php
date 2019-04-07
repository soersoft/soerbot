<?php

namespace SoerBot\Commands\PhpFact\Implementations;

use SoerBot\Commands\PhpFact\Exceptions\PhpFactException;
use SoerBot\Commands\PhpFact\Abstractions\StorageInterface;

class PhpFacts
{
    /**
     * Minimum pattern length.
     */
    public const SEARCH_MIN_LENGTH = 3;
    /**
     * Maximum pattern length.
     */
    public const SEARCH_MAX_LENGTH = 32;

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
            throw new PhpFactException('Facts array is empty.');
        }
    }

    /**
     * @param int $position
     *
     * @return bool|string
     */
    public function get(int $position)
    {
        $index = --$position;
        if (array_key_exists($index, $this->facts)) {
            return $this->facts[$index];
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
     * Search PHP facts by pattern.
     *
     * @param string $pattern
     * @throws PhpFactException
     * @return array
     */
    public function search(string $pattern): array
    {
        $pattern = trim($pattern);
        $length = mb_strlen($pattern);

        if ($length === 0) {
            throw new PhpFactException('Passed pattern is empty.');
        }

        if ($length < self::SEARCH_MIN_LENGTH) {
            throw new PhpFactException('Passed pattern is less than minimum ' . self::SEARCH_MIN_LENGTH . ' chars.');
        }

        if ($length > self::SEARCH_MAX_LENGTH) {
            throw new PhpFactException('Passed pattern is more than maximum ' . self::SEARCH_MAX_LENGTH . ' chars.');
        }

        $found = [];

        foreach ($this->facts as $fact) {
            if (preg_match('/\b' . $pattern . '\b/iSu', $fact)) {
                $found[] = $fact;
            }
        }

        return $found;
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
