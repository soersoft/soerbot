<?php

namespace SoerBot\Commands\Devs;

class DevsCommand extends \CharlotteDunois\Livia\Commands\Command
{

    /**
     * @var array
     */
    protected $topics;

    /**
     * @var string
     */
    protected $path = __DIR__ . '/store/';

    /**
     * @var string
     */
    protected $extension = '.topic.md';


    /**
     * @param \CharlotteDunois\Livia\LiviaClient $client
     *
     * @throws \Exception
     */
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {

        $this->topics = $this->getTopics($this->path);

        parent::__construct($client, [
            'name' => 'devs', // Give command name
            'aliases' => ['dev'],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'Команда $devs выводит важные топики.', // Fill the description
            'guildOnly' => false,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'guarded' => true,
            'args' => [ // If you need some variables you should either fill this section or remove it
                [
                    'key' => 'topic',
                    'label' => 'topic',
                    'prompt' => 'Укажите топик: ' . $this->stringifyTopics($this->topics). '.',
                    'type' => 'string',
                ],
            ],
        ]);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        if (!empty($args) && in_array($args['topic'], $this->topics)) {
            return $message->say(file_get_contents($this->path . $args['topic'] . $this->extension));
        }

        return $message->say('Укажите топик: ' . $this->stringifyTopics($this->topics). '.');
    }

    protected function stringifyTopics(array $topics): string
    {
        return implode(', ', $topics);
    }

    /**
     * @param string $path
     * @return array
     *
     * @throws \Exception
     */
    protected function getTopics(string $path): array
    {
        if (!is_dir($path)) {
            throw new \Exception('DevsCommand error. You must provide valid directory.');
        }

        $files = [];

        foreach (new \DirectoryIterator($path) as $file) {
            if ($file->isFile() && substr($file->getBasename(), -9, 10) === $this->extension) {
                $files[] = str_replace($this->extension, '', $file->getBasename());
            }
        }

        if (empty($files)) {
            throw new \Exception('DevsCommand error. You must provide directory with right files.');
        }

        return $files;
    }

    public function serialize()
    {
        return [];
    }

    
}
