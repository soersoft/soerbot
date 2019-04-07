<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;

class LeaderboardRemoveCommandTest extends TestCase
{
    private $command;
    private $client;
    private $users;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/Leaderboard/remove.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(2))->method('has')->willReturn(true);
        $registry->expects($this->exactly(4))->method('__get')->with('types')->willReturn($types);
        $this->client->expects($this->exactly(4))->method('__get')->with('registry')->willReturn($registry);

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testLeaderboardBasics()
    {
        $this->assertEquals($this->command->name, 'leaderboard-remove-rewards');
        $this->assertEquals($this->command->description, 'Удаляет все награды указанного типа у участника');
        $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testLeaderboardAddArguments()
    {
        $this->assertEquals(sizeof($this->command->args), 2);
        foreach ($this->command->args as $arg) {
            $this->assertArrayHasKey('key', $arg);
            $this->assertArrayHasKey('label', $arg);
            $this->assertArrayHasKey('prompt', $arg);
            $this->assertArrayHasKey('type', $arg);
        }
    }

    public function testResponseToTheDiscord(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');

        $this->users = $this->getMockBuilder('UserModel')->setMethods(['removeRewardsByType'])->getMock();

        $promise = new Promise(function () {
        });

        $commandMessage->expects($this->once())->method('say')->with('Награды удаленны')->willReturn($promise);

        $this->users->method('removeRewardsByType')->willReturn(true);

        $this->setPrivateVariableValue($this->command, 'users', $this->users);

        $user = $this->createMock('\CharlotteDunois\Yasmin\Models\User');
        $user->expects($this->once())->method('__get')->with('username')->willReturn('username');

        $this->command->run($commandMessage, new ArrayObject(['name' => $user, 'emoji' => '🏅']), false);
    }
}
