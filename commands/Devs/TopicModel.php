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
     * @throws \Exception
     */
    protected function __construct(string $file)
    {
        $this->filePath = $file;
    }

    /**
     * @param string $file
     * @throws \Exception
     * @return array
     */
    public static function create(string $file): array
    {
        if (!file_exists($file)) {
            throw new \Exception('DevsCommand error: file ' . $file . ' does not exists.');
        }

        if (!self::isTopic($file)) {
            throw new \InvalidArgumentException('DevsCommand error: file ' . $file . ' has wrong extension and is not valid topic file.');
        }

        return [self::getKey($file) => new self($file)];
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
     * Returns cleaned topic name from file path.
     *
     * @param string $filePath
     * @return string
     */
    protected static function getKey(string $filePath): string
    {
        return str_replace(self::EXTENSION, '', pathinfo($filePath, PATHINFO_BASENAME));
    }

    /**
     * Check if file has right topic extension.
     *
     * @return bool
     */
    protected static function isTopic(string $filePath): bool
    {
        $length = mb_strlen(self::EXTENSION);

        return substr($filePath, -$length) === self::EXTENSION;
    }
}
