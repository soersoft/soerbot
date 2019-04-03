<?php

namespace SoerBot\Commands;

use SoerBot\Configurator;

abstract class Command extends \CharlotteDunois\Livia\Commands\Command
{
    protected $commandName = '';

    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        $this->loadConfig();
        parent::__construct($client, $this->makeParamsForCommand());
    }

    public function __get($name)
    {
        if (\property_exists($this, $name)) {
            return $this->$name;
        }

        if (array_key_exists($name, $config = Configurator::get($this->commandName))) {
            return $config[$name];
        }

        throw new \RuntimeException('Unknown property ' . \get_class($this) . '::$' . $name);
    }

    protected function loadConfig()
    {
        preg_match('/\w+Command$/i', static::class, $m);
        $this->commandName = str_replace('command', '', strtolower($m[0]));
        $configFile = __DIR__ . '/Leaderboard/config/' . $this->commandName . '.config.yaml';
        Configurator::merge($configFile);
    }

    protected function makeParamsForCommand()
    {
        return [
          'name' => Configurator::get('leaderboard')['name'],
          'aliases' => [''],
          'group' => Configurator::get('leaderboard')['group'],
          'description' => Configurator::get('leaderboard')['description'],
          'guildOnly' => false,
          'throttling' => [
            'usages' => 5,
            'duration' => 10,
          ],
          'guarded' => true,
          'args' => [],
        ];
    }
}
