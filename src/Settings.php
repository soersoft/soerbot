<?php

namespace SoerBot;

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
     * @return Settings
     * @throws Exceptions\ConfigurationFileNotFound
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get setting by key
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
     * Get all settings
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
     * Magic __clone method
     */
    private function __clone()
    {
    }
}