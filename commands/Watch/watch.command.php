<?php
return function ($client) {

  return (new class($client) extends \CharlotteDunois\Livia\Commands\Command
  {
    function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
      parent::__construct($client, array(
        'name' => 'watch', // Give command name
        'aliases' => array(),
        'group' => 'utils', // Group in ['command', 'util']
        'description' => 'Check every message', // Fill the description
        'guildOnly' => false,
        'throttling' => array(
          'usages' => 5,
          'duration' => 10
        ),
        'guarded' => true,
        'args' => array()
      ));
      $client->on('message', function($arg) {var_dump($arg);});
    }

    function watch($arg) {
      $this->client->emit('stop');
    } 

    function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
      return  $message->say('...');
    }
  });
};