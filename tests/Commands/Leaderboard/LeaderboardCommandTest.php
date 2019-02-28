<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;

class LeaderboardCommandTest extends TestCase
{
    private $command;
    private $client;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/Leaderboard/leaderboard.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testLeaderboardBasics()
    {
        $this->assertEquals($this->command->name, 'leaderboard');
        $this->assertEquals($this->command->description, 'Выводит таблицу участников и набранные очки');
        $this->assertEquals($this->command->groupID, 'utils');

    }

    public function testSimpleResponseToTheDiscord(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });

        $filePath = __DIR__ . '/../../Fixtures/leaderboard.tmp.txt';
        $leaderboardStaticMessage = file_get_contents($filePath);

        $commandMessage->expects($this->once())
          ->method('say')
          ->with($leaderboardStaticMessage)
          ->willReturn($promise);

        $this->command->run($commandMessage, new ArrayObject(), false);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
