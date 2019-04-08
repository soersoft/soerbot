<?php

namespace SoerBot\Commands;

use SoerBot\Configurator;

abstract class SoerCommand extends \CharlotteDunois\Livia\Commands\Command
{
    protected $commandName = '';

    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        $this->commandName = $this->makeCommandName(static::class);
        $this->loadConfig($this->makeConfigName(static::class));
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

    protected function loadConfig($configName)
    {
        Configurator::merge($configName, $this->commandName);
    }

    protected function makeParamsForCommand()
    {
        if (is_null($this->commandName) || is_null($this->getCommandOption('description'))) {
            throw new \InvalidArgumentException('Please set up right values for config file and command description');
        }

        return [
          'name' => $this->commandName,
          'aliases' => $this->getCommandOption('aliases', ['']),
          'group' => $this->getCommandOption('group', 'utils'),
          'description' => $this->getCommandOption('description'),
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
        return Configurator::get($this->commandName . '.' . $optionName, $default);
    }

    protected function makeCommandName($classname)
    {
        preg_match('/(\w+)Command/i', strtolower($classname), $m);

        return $m[1] ?? null;
    }

    protected function makeConfigName($classname)
    {
        preg_match('/Commands\\\(\w+)/i', $classname, $m);

        return $m[1] ? __DIR__ . '/' . $m[1] . '/config/' . $this->commandName . '.config.yaml' : null;
    }
}
