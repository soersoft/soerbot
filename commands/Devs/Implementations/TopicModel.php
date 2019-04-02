<?php

namespace SoerBot\Commands\Devs\Implementations;

use SoerBot\Commands\Devs\Exceptions\TopicException;
use SoerBot\Commands\Devs\Exceptions\TopicExceptionFileNotFound;

class TopicModel
{
    protected $directory = __DIR__ . '/../store/';
    protected $extension = '.topic.md';

    /**
     * @var string
     */
    protected $content;

    /**
     * TopicModel constructor.
     * @param string $topic
     * @param string $directory
     * @throws TopicException
     * @throws TopicExceptionFileNotFound
     */
    public function __construct(string $topic, string $directory = '')
    {
        $this->content = $this->load($topic, $directory);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $topic
     * @param string $directory
     * @throws TopicException
     * @throws TopicExceptionFileNotFound
     * @return string
     */
    protected function load(string $topic, string $directory): string
    {
        if (!empty($directory)) {
            if (!is_dir($directory)) {
                throw new TopicExceptionFileNotFound('Directory ' . $directory . ' does not exists. Check directory source.');
            }

            $this->directory = $directory;
        }

        $file = $this->directory . $topic . $this->extension;

        if (!file_exists($file)) {
            throw new TopicExceptionFileNotFound('File ' . $file . ' does not exists. Check file source.');
        }

        if (!$this->isTopic($file)) {
            throw new TopicException('File ' . $file . ' has wrong extension. Check object input.');
        }

        $content = @file_get_contents($file);

        if (empty($content)) {
            throw new TopicException('File ' . $file . ' is empty. Check file source.');
        }

        return $content;
    }

    /**
     * Check if file has right topic extension.
     *
     * @param string $file
     * @return bool
     */
    protected function isTopic(string $file): bool
    {
        $length = mb_strlen($this->extension);

        return mb_substr($file, -$length) === $this->extension;
    }
}
