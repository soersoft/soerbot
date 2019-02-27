<?php
return function ($client) {
    return (new class($client) extends \CharlotteDunois\Livia\Commands\Command
    {
        function __construct(\CharlotteDunois\Livia\LiviaClient $client)
        {
            parent::__construct($client, array(
                'name' => 'leaderboard', // Give command name
                'aliases' => array(),
                'group' => 'utils', // Group in ['command', 'util']
                'description' => 'Выводит состояние leaderboard из канала #leaderboard', // Fill the description
                'guildOnly' => false,
                'throttling' => array(
                    'usages' => 5,
                    'duration' => 10
                ),
                'guarded' => true,
                'args' => array()
            ));
        }

        function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            $leaderboard_text = <<< EOT
@Heisenberg (Александр)
:star::star::star::star::star: 
:medal::medal:

@ucorp  (Аслан)
:star::star::star::star::star:
:medal:

@IvanK (Иван)
:star::star::star:
:medal: 

@simbiosse (Руслан)
:star: :star: :star:

@Александр Семин
:star: :star:

@LoveFist (Михаил)
:star: :star: :star: :star:

 @DanielWeiser 
:star: :star:

@MikesoWeb 
:star: :star:

@iaptekar (Иван)
:star: :star:

@resident01 
:star::star:

@Andrey Kustov  (Андрей)
:star:
EOT;

            return $message->say($leaderboard_text);
        }
    });
};