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
     *
     * @param string $file
     * @throws \Exception
     */
    public function __construct(string $file = __DIR__ . '/../store/phpfact.txt')
    {
        $this->data = $this->fetch($file);
    }

    /**
     * Returns data to client.
     *
     * @return array
     */
    public function get(): array
    {
        return $this->data;
    }

    /**
     * Initialize state.
     *
     * @param string $file
     * @throws StorageException
     * @return array
     */
    private function fetch(string $file): array
    {
        if (!file_exists($file)) {
            throw new StorageException('File ' . $file . ' does not exists. Check source file.');
        }

        $data = @file($file, FILE_IGNORE_NEW_LINES);

        if (empty($data)) {
            throw new StorageException('File ' . $file . ' is empty. Check source file.');
        }

        return $data;
    }
}
