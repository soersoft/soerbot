<?php

namespace SoerBot\Commands\PhpFact\Implementations;

use SoerBot\Commands\PhpFact\Exceptions\StorageException;
use SoerBot\Commands\PhpFact\Abstractions\StorageInterface;

class FileStorage implements StorageInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * Storage constructor.
     * @param string $file
     * @throws \Exception
     */
    public function __construct(string $file = __DIR__ . '/../store/phpfact.txt')
    {
        if (!file_exists($file)) {
            throw new StorageException('File ' . $file . ' does not exits.');
        }

        $this->data = @file($file, FILE_IGNORE_NEW_LINES);

        if (empty($this->data)) {
            throw new StorageException('File ' . $file . ' was empty.');
        }
    }

    /**
     * @return array
     */
    public function fetch(): array
    {
        return $this->data;
    }
}
