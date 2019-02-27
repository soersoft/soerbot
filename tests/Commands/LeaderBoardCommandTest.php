<?php

namespace Tests\Commands;

use Tests\TestCase;
use React\Promise\Promise;
use ArrayObject;

class LeaderBoardCommandTest extends TestCase
{

    private $command;
    private $leaderboard_text;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../commands/LeaderBoard/LeaderBoard.command.php';
        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $this->command = $commandCreate($this->client);

        $this->leaderboard_text = <<< EOT
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

        parent::setUp();
    }

    public function testLeaderBoardBasics()
    {
        $this->assertEquals($this->command->name, 'leaderboard');
        $this->assertEquals($this->command->description, 'Выводит состояние leaderboard из канала #leaderboard');
        $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testSimpleResponseToTheDiscord(): void
    {

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });

        $commandMessage->expects($this->once())->method('say')->with($this->leaderboard_text)->willReturn($promise);

        $this->command->run($commandMessage, new ArrayObject(), false);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}