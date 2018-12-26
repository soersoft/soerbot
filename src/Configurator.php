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
     * @param $key
     * @param null $default
     * @throws ConfigurationFileNotFound
     * @return mixed|null
     */
    public static function get($key, $default = null)
    {
        // Если данные отсутствют, пробуем их загрузить.
        if (empty(self::$configurations)) {
            self::load();
        }

        return key_exists($key, self::$configurations) ? self::$configurations[$key] : $default;
    }

    /**
     * @throws ConfigurationFileNotFound
     */
    public static function load()
    {
        if (!file_exists(self::$path)) {
            throw new ConfigurationFileNotFound();
        }

        self::$configurations = Yaml::parseFile(self::$path);
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
