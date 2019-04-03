<?php

namespace SoerBot;

use Symfony\Component\Yaml\Yaml;

class Settings
{
    /**
     * @var Settings
     */
    private static $instance = null;

    /**
     * @var array
     */
    private $settings = [];

    /**
     * Get instance method.
     *
     * @throws Exceptions\ConfigurationFileNotFound
     * @return Settings
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function init(\CharlotteDunois\Livia\Commands\Command $command, $dir = __DIR__): array
    {
        $namespace = $command->__get('name');

        $this->settings[$namespace] = [
            'name' => $command->__get('name'),
            'description' => $command->__get('description'),
            'path' => $dir,
        ];

        if (is_dir($dir . '/config/') && file_exists($dir . '/config/config.yaml')) {
            $localConfig = Yaml::parseFile($dir . '/config/config.yaml');
            $this->settings[$namespace] = array_merge($this->settings[$namespace], $localConfig);
        }

        return $this->settings;
    }

    /**
     * Get setting by key.
     *
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->settings) ? $this->settings[$key] : $default;
    }

    /**
     * Get all settings.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->settings;
    }

    /**
     * Settings constructor.
     * @throws Exceptions\ConfigurationFileNotFound
     */
    private function __construct()
    {
        $this->settings = Configurator::get('commands');
    }

    /**
     * Magic __clone method.
     */
    private function __clone()
    {
    }
}
