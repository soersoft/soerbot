<?php
/**
 *
 */

namespace SoerBot\Commands\PhpFact\Implementations;


class Storage
{
    /**
     * @var array
     */
    private $data;

    /**
     * Storage constructor
     * @param string $file
     * @throws \Exception
     */
    public function __construct(string $file = __DIR__ . '/../store/phpfact.txt')
    {
        if (!file_exists($file)) {
            throw new \Exception('File ' . $file . ' does not exits.');
        }

        $this->data = @file($file, FILE_IGNORE_NEW_LINES);

        if (empty($this->data)) {
            throw new \Exception('File ' . $file . ' was empty.');
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