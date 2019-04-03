<?php

use SoerBot\Commands\Devs\DevsCommand;

return function ($client) {
    return new DevsCommand($client, [
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
                'prompt' => 'Укажите топик или list для получения топиков.',
                'type' => 'string',
            ],
        ],
        'storePath' => __DIR__ . '/store/',
    ]);
};
