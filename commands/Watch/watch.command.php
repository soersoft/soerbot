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
      $client->on('message', [$this, watch]);
    }

    function watch(CharlotteDunois\Yasmin\Models\Message $arg) {
      if ($arg->author->username == 'Spidey Bot' && $arg->embeds[0]->color == 3066993) 
      {
        foreach($arg->embeds[0]->fields as $field) 
        {
          if ($field['name'] == 'Branch' && strpos($field['value'], 'develop') > 0)
          {
            $this->client->emit('stop');
            print('it seems test is ok');
          }
        }
        
      }
    } 

    function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
      return  $message->say('...');
    }
  });
};