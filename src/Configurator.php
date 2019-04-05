<?php

namespace SoerBot;

use Symfony\Component\Yaml\Yaml;
use SoerBot\Exceptions\ConfigurationFileNotFound;

/**
 * Class Configurator.
 *
 * @package SoerBot
 */
class Configurator
{
    /**
     * @var string
     */
    private static $path = __DIR__ . '/../config.yaml';

    /**
     * @var array
     */
    private static $configurations = [];

    /**
     * @return string
     */
    public static function getConfigPath()
    {
        return self::$path;
    }

    /**
     * @param string $path
     */
    public static function setConfigPath(string $path)
    {
        self::$path = $path;
    }

    /**
     * Allows getting values from config. You can use dot notation as well (e.g. 'foo.bar').
     * @param $key
     * @param null $default
     * @throws ConfigurationFileNotFound
     * @return mixed|null
     */
    public static function get($key, $default = null)
    {
        if (is_null($key)) {
            return $default;
        }

        if (empty(self::$configurations)) {
            try {
                self::load();
            } catch (ConfigurationFileNotFound $error) {
                exit($error->getMessage());
            }
        }

        if (array_key_exists($key, self::$configurations)) {
            return self::$configurations[$key];
        }

        $configurations = self::$configurations;

        foreach (explode('.', $key) as $part) {
            if (is_array($configurations) && array_key_exists($part, $configurations)) {
                $configurations = $configurations[$part];
            } else {
                return $default;
            }
        }

        return $configurations;
    }

    /**
     * @throws ConfigurationFileNotFound
     */
    public static function load()
    {
        if (!file_exists(self::$path)) {
            throw new ConfigurationFileNotFound('To start the SoerBot, configuration file ' . self::$path
              . " is require. Please create config.yaml, use config.example.yaml as example.\n");
        }

        self::$configurations = Yaml::parseFile(self::$path);
    }

    /**
     * Appends settings from the file to already existed settings.
     * @param string $path Path to additional configuration file
     * @throws ConfigurationFileNotFound
     */
    public static function merge($path)
    {
        $oldConfigurations = self::$configurations;
        $oldPath = self::$path;
        self::$path = $path;
        self::load();
        self::$configurations = array_merge(self::$configurations, $oldConfigurations);
        self::$path = $oldPath;
    }

    /**
     * @throws ConfigurationFileNotFound
     * @return array
     */
    public static function all()
    {
        if (empty(self::$configurations)) {
            self::load();
        }

        return self::$configurations;
    }
}
