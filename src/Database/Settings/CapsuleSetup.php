<?php

namespace SoerBot\Database\Settings;

use SoerBot\Configurator;

class CapsuleSetup
{
    /**
     * Configuration Capsule.
     * @throws \SoerBot\Exceptions\ConfigurationFileNotFound
     */
    public static function setup()
    {
        $capsule = self::makeCapsule();
        $capsule->addConnection(self::makeConnectionConfig());
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    /**
     * @throws \SoerBot\Exceptions\ConfigurationFileNotFound
     * @return array
     */
    private static function makeConnectionConfig(): array
    {
        return Configurator::get('db-connection', []);
    }

    /**
     * @return Capsule
     */
    private static function makeCapsule(): Capsule
    {
        return new Capsule();
    }
}
