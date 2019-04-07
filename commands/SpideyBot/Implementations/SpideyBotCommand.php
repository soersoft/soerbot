<?php

namespace SoerBot\Commands\SpideyBot\Implementations;

use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;
use SoerBot\Commands\SpideyBot\WatcherActor\SpideyBotWatcherActor;

class SpideyBotCommand extends Command
{
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'spidey-bot', // Give command name
            'aliases' => [],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'Github CI bot', // Fill the description
            'guildOnly' => false,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'guarded' => true,
            'args' => [],
        ]);

        $client->emit('RegisterWatcher', new SpideyBotWatcherActor($client));
    }

    public function run(CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        return  $message->say('...');
    }
}
