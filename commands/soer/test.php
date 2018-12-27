<?php
/**
 * Livia
 * Copyright 2017-2018 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Livia/blob/master/LICENSE
*/

return function ($client) {
    return (new class($client) extends \CharlotteDunois\Livia\Commands\Command {
        function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
            parent::__construct($client, array(
                'name' => 'test',
                'aliases' => array(),
                'group' => 'commands',
                'description' => 'Sends a ping and measures the latency between command message and ping message. It will also display websocket ping.',
                'guildOnly' => false,
                'throttling' => array(
                    'usages' => 5,
                    'duration' => 10
                ),
                'guarded' => true
            ));
        }
        
        function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern) {
            return $message->say('Pinging...')->then(function ($msg) use ($message) {
                $time = \CharlotteDunois\Yasmin\Utils\Snowflake::deconstruct($msg->id)->timestamp - \CharlotteDunois\Yasmin\Utils\Snowflake::deconstruct($message->id)->timestamp;
                
		$this->client->emit('stop');
                $ping = $this->client->getPing();
                if(!\is_int($ping)) {
                    $ping = 0;
                }
                
                return $msg->edit($message->author.' Pong! The message round-trip took '.\ceil(($time * 1000)).'ms. The WS heartbeat is '.$ping.'ms.');
            });
        }
    });
};
