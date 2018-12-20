<?php
if (!isset($argv[1]) || !isset($argv[2])) {
    print("Usage: php run.php CLIENT_ID SECRET_KEY");
    exit(0);
} 

require_once(__DIR__.'/vendor/autoload.php');

$loop = \React\EventLoop\Factory::create();
$client = new \CharlotteDunois\Livia\LiviaClient(array(
    'owners' => array($argv[1]),
    'unknownCommandResponse' => false,
    'commandPrefix' => '$',
    'owners' => array('406740817487593472')
), $loop);

// Registers default commands, command groups and argument types
$client->registry->registerDefaults();

// Register the command group for our example command
$client->registry->registerGroup(array('id' => 'moderation', 'name' => 'Moderation'));

// Register our commands (this is an example path)
$client->registry->registerCommandsIn(__DIR__.'/commands/');

// If you have created a command, like the example above, you now have registered the command.

$client->on('ready', function () use ($client) {
    echo 'Logged in as '.$client->user->tag.' created on '.
           $client->user->createdAt->format('d.m.Y H:i:s').PHP_EOL;
});
print("Start");
$client->login($argv[2])->done();
$loop->run();
