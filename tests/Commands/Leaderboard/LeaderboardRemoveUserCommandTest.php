<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use CharlotteDunois\Livia\LiviaClient;
use SoerBot\Commands\Leaderboard\Implementations\LeaderboardRemoveUser;

class LeaderboardRemoveUserCommandTest extends TestCase
{
    /** @var LeaderboardRemoveUser */
    private $command;

    /** @var LiviaClient */
    private $client;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/Leaderboard/removeuser.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(1))->method('has')->willReturn(true);
        $registry->expects($this->exactly(2))->method('__get')->with('types')->willReturn($types);
        $this->client->expects($this->exactly(2))->method('__get')->with('registry')->willReturn($registry);

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    /**
     * Exceptions.
     */

    /**
     * Corner cases.
     */

    /**
     * Functionality.
     */
    public function testConstructorMakeRightCommand()
    {
        $this->assertEquals($this->command->name, 'leaderboard-remove-user');
        $this->assertEquals($this->command->description, 'Удаляет участника из списка');
        $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testConstructorMakeRightCommandWithArguments()
    {
        $this->assertEquals(count($this->command->args), 1);

        foreach ($this->command->args as $arg) {
            $this->assertArrayHasKey('key', $arg);
            $this->assertArrayHasKey('label', $arg);
            $this->assertArrayHasKey('prompt', $arg);
            $this->assertArrayHasKey('type', $arg);
        }
    }

    public function testRunSayWhenUserNotExist()
    {
        $user = 'not_exist';

        $userModel = $this->createMock('SoerBot\Commands\Leaderboard\Implementations\UserModel');
        $userModel->expects($this->once())->method('hasUser')->willReturn(false);

        $this->setPrivateVariableValue($this->command, 'users', $userModel);

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $commandMessage->expects($this->once())->method('say')->with('Пользователь ' . $user . ' не существует');

        $this->command->run($commandMessage, new ArrayObject(['name' => $user]), false);
    }

    public function testRunSayWhenUserExist()
    {
        $user = '@ucorp';

        $userModel = $this->createMock('SoerBot\Commands\Leaderboard\Implementations\UserModel');
        $userModel->expects($this->once())->method('hasUser')->willReturn(true);
        $userModel->expects($this->once())->method('remove')->with($user)->willReturn(true);

        $this->setPrivateVariableValue($this->command, 'users', $userModel);

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $commandMessage->expects($this->once())->method('say')->with('Пользователь ' . $user . ' успешно удален');

        $this->command->run($commandMessage, new ArrayObject(['name' => $user]), false);
    }

    public function testHasPermission()
    {
        $client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(1))->method('has')->willReturn(true);
        $registry->expects($this->exactly(2))->method('__get')->with('types')->willReturn($types);
        $client->expects($this->exactly(2))->method('__get')->with('registry')->willReturn($registry);

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $commandMock = $this->getMockBuilder('SoerBot\Commands\Leaderboard\Implementations\LeaderboardRemoveUser')
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
        $role = $this->createMock('\CharlotteDunois\Yasmin\Models\Role');
        $member = $this->createMock('\CharlotteDunois\Yasmin\Models\GuildMember');
        $roleStorage = [$role];

        $commandMessage->expects($this->once())->method('__get')->with('member')->willReturn($member);
        $member->expects($this->once())->method('__get')->with('roles')->willReturn($roleStorage);
        $role->expects($this->once())->method('__get')->with('name')->willReturn($roleName);

        $permission = $this->command->hasAllowedRole($commandMessage);
        $roles = $this->getPrivateVariableValue($this->command, 'allowedRoles');

        if (!in_array($roleName, $roles)) {
            $this->assertFalse($permission);
        } else {
            $this->assertTrue($permission);
        }
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
