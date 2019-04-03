<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;

class LeaderboardAddCommandTest extends TestCase
{
    private $command;
    private $client;
    private $users;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/Leaderboard/add.command.php';

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
        $this->assertEquals($this->command->name, 'leaderboard-add');
        $this->assertEquals($this->command->description, 'Добавляет награду участнику');
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

    public function testHasPermission()
    {
        $client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(2))->method('has')->willReturn(true);
        $registry->expects($this->exactly(4))->method('__get')->with('types')->willReturn($types);
        $client->expects($this->exactly(4))->method('__get')->with('registry')->willReturn($registry);

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $commandMock = $this->getMockBuilder('SoerBot\Commands\Leaderboard\Implementations\LeaderboardAddCommand')
        ->setConstructorArgs([$client])
        ->setMethodsExcept(['hasPermission'])
            ->getMock();
        $commandMock->expects($this->once())->method('hasAllowedRole')->willReturn(false);

        $this->assertFalse($commandMock->hasPermission($commandMessage));
    }

    /**
     * @dataProvider differentRolesProvider
     */
    public function testHasALlowedRole($roleName): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $role = $testRole = $this->createMock('\CharlotteDunois\Yasmin\Models\Role');
        $member = $this->createMock('\CharlotteDunois\Yasmin\Models\GuildMember');
        $roleStorage = [$role];

        $commandMessage->expects($this->once())->method('__get')->with('member')->willReturn($member);
        $member->expects($this->once())->method('__get')->with('roles')->willReturn($roleStorage);
        $role->expects($this->once())->method('__get')->with('name')->willReturn($roleName);

        $permission = $this->command->hasAllowedRole($commandMessage);

        if (!in_array($roleName, $this->command->allowRoles)) {
            $this->assertFalse($permission);
        } else {
            $this->assertTrue($permission);
        }
    }

    public function testResponseToTheDiscord(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $this->users = $this->getMockBuilder('UserModel')->setMethods(['incrementReward'])->getMock();
        $user = $this->createMock('\CharlotteDunois\Yasmin\Models\User');

        $promise = new Promise(function () {
        });

        $commandMessage->expects($this->once())->method('say')->with('Награда добавлена')->willReturn($promise);

        $this->users->method('incrementReward')->willReturn(true);

        $this->setPrivateVariableValue($this->command, 'users', $this->users);

        $user->expects($this->once())->method('__get')->with('username')->willReturn('username');

        $this->command->run($commandMessage, new ArrayObject(['name' => $user, 'emoji' => '🏅']), false);
    }

    public function differentRolesProvider()
    {
        return [
            ['@everyone'],
            ['product owner'],
            ['куратор'],
        ];
    }
}
