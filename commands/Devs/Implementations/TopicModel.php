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
     * @throws TopicException
     */
    public function __construct(string $topic)
    {
        $this->content = $this->load($topic);
    }

    /**
     * @param string $topic
     * @throws TopicException
     * @throws TopicExceptionFileNotFound
     * @return string
     */
    public function load(string $topic): string
    {
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
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
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
