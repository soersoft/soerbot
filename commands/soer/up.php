<?php

use SoerBot\Commands\Up;

return function ($client) {
    return (new Up($client, []));
};