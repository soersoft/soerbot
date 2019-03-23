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
     * @param string $filePath
     *
     * @throws \Exception
     */
    public function __construct(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception('DevsCommand error. You must provide valid file path.');
        }

        if (!self::isTopic($filePath)) {
            throw new \Exception('DevsCommand error. You must provide valid topic file.');
        }

        $this->filePath = $filePath;
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