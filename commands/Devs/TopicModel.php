<?php

namespace SoerBot\Commands\Devs;

class TopicModel
{
    protected const EXTENSION = '.topic.md';

    /**
     * @var string
     */
    protected $filePath;

    /**
     * TopicModel constructor.
     * @param string $file
     *
     * @throws \Exception
     */
    protected function __construct(string $file)
    {
        $this->filePath = $file;
    }

    public static function create(string $file)
    {
        if (!file_exists($file)) {
            throw new \Exception('DevsCommand error: file ' . $file . ' does not exists.');
        }

        if (!self::isTopic($file)) {
            throw new \InvalidArgumentException('DevsCommand error: file ' . $file . ' has wrong extension and is not valid topic file.');
        }

        return new self($file);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return file_get_contents($this->filePath);
    }

    /**
     * Check if file has right topic extension
     *
     * @return bool
     */
    public static function isTopic(string $filePath): bool
    {
        $length = mb_strlen(self::EXTENSION);

        return substr($filePath, -$length) === self::EXTENSION;
    }

    /**
     * Returns cleaned topic name from file path
     *
     * @param string $filePath
     * @return string
     */
    public static function getCleanName(string $filePath): string
    {
        return str_replace(self::EXTENSION, '', pathinfo($filePath, PATHINFO_BASENAME));
    }
}