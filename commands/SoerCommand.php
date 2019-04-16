<?php

namespace SoerBot\Commands;

use SoerBot\Configurator;
use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\Commands\Command;

abstract class SoerCommand extends Command
{
    protected $configName = '';

    public function __construct(LiviaClient $client)
    {
        $this->configName = $this->makeConfigName();

        if (empty($this->configName)) {
            throw new \Exception('Command name not found in the class name.');
        }

        $pathToConfig = $this->makePathToConfig();

        if (empty($pathToConfig) || !file_exists($pathToConfig)) {
            throw new \Exception("Wrong path or the config file doesn't exist");
        }

        $this->loadConfig($pathToConfig);

        parent::__construct($client, $this->makeParamsForCommand());
    }

    public function __get($name)
    {
        if (\property_exists($this, $name)) {
            return $this->$name;
        }

        if (array_key_exists($name, $config = Configurator::get($this->configName))) {
            return $config[$name];
        }

        throw new \RuntimeException('Unknown property ' . \get_class($this) . '::$' . $name);
    }

    protected function makeParamsForCommand()
    {
        if (is_null($name = $this->getCommandOption('name'))) {
            throw new \InvalidArgumentException('You have to set up name for the command in the configuration file');
        }

        if (is_null($description = $this->getCommandOption('description'))) {
            throw new \InvalidArgumentException("You have to set up description for the command in \
            the configuration file");
        }

        return [
          'name' => $name,
          'description' => $description,
          'group' => $this->getCommandOption('group', 'utils'),
          'aliases' => $this->getCommandOption('aliases', ['']),
          'guildOnly' => $this->getCommandOption('guildOnly', false),
          'throttling' => [
            'usages' => 5,
            'duration' => 10,
          ],
          'guarded' => $this->getCommandOption('guarded', true),
          'args' => $this->getCommandOption('args', []),
        ];
    }

    protected function getCommandOption($optionName, $default = null)
    {
        return Configurator::get($this->configName . '.' . $optionName, $default);
    }

    protected function loadConfig($path)
    {
        Configurator::merge($path, $this->configName);
    }

    protected function makeConfigName()
    {
        preg_match('/(\w+)Command/i', strtolower(get_class($this)), $m);

        return $m[1] ?? null;
    }

    protected function makePathToConfig()
    {
        preg_match('/Commands\\\(\w+)/i', get_class($this), $m);

        return $m[1] ? __DIR__ . '/' . $m[1] . '/config/' . $this->configName . '.config.yaml' : null;
    }
}
