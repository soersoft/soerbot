<?php
/**
 *
 */

namespace SoerBot\Commands\Devs;


class TopicCollection
{
    /**
     * @var array
     */
    protected $topics;

    /**
     * TopicCollection constructor.
     *
     * @param string $path
     *
     * @throws \Exception
     */
    public function __construct(string $path = __DIR__ . '/store/')
    {
        $this->topics = $this->setupTopics($path);
    }

    /**
     * @param string $key
     * @return TopicModel
     */
    public function getTopic(string $key): ?TopicModel
    {
        return $this->topics[$key] ?? null;
    }

    /**
     * @return string
     */
    public function getContent(string $key): ?string
    {
        return isset($this->topics[$key]) ? $this->topics[$key]->getContent() : null;
    }

    /**
     * @return array
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @return string
     */
    public function getNames(): string
    {
        return $this->stringifyTopics();
    }

    /**
     * @param string $path
     * @return array
     *
     * @throws \Exception
     */
    protected function setupTopics(string $path): array
    {
        if (!is_dir($path)) {
            throw new \Exception('DevsCommand error. You must provide valid directory.');
        }

        $topics = [];

        foreach (new \DirectoryIterator($path) as $file) {
            if ($file->isFile() && TopicModel::isTopic($file->getBasename())) {
                $key = TopicModel::getCleanName($file->getBasename());
                $topics[$key] = new TopicModel($file->getRealPath());
            }
        }

        if (empty($topics)) {
            throw new \Exception('DevsCommand error. You must provide directory with right files.');
        }

        return $topics;
    }

    /**
     * @return string
     */
    protected function stringifyTopics(): string
    {
        ksort($this->topics);
        return implode(', ', array_keys($this->topics));
    }
}