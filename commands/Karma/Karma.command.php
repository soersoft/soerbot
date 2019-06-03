<?php

use SoerBot\Commands\Karma\Implementations\KarmaCommand;
use SoerBot\Commands\Karma\WatcherActor\KarmaWatcherActor;
use SoerBot\Commands\Karma\Implementations\KarmaListener;

return function ($client) {
    $client->emit('RegisterWatcher', new KarmaWatcherActor($client));
    new KarmaListener($client);

    return new KarmaCommand($client);
};
