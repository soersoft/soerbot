<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;

class KarmaCommandTest extends TestCase
{
    private $command;

    /**
     * @var \CharlotteDunois\Yasmin\Models\User
     */
    private $user;

    /**
     * @var \SoerBot\Commands\Karma\Implementations\UserModel
     */
    private $userModel;

    /**
     * @var \CharlotteDunois\Livia\CommandMessage
     */
    private $commandMessage;

    protected function setUp()
    {
        $commandCreate = require __DIR__.'/../../../commands/Karma/Karma.command.php';

        $client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');

        $this->user = $this->createMock('\CharlotteDunois\Yasmin\Models\User');
        $this->userModel = $this->createMock('\SoerBot\Commands\Karma\Implementations\UserModel');
        $this->commandMessage = $this->createMock('\CharlotteDunois\Livia\CommandMessage');

        $this->command = $commandCreate($client);

        parent::setUp();
    }

    public function testRun(): void
    {
        $userName = 'username';

        $promise = new Promise(function () {
        });

        $this->userModel->expects($this->once())->method('getKarma')->with($userName)->willReturn(20);
        $this->commandMessage->expects($this->once())->method('__get')->with('author')->willReturn($this->user);
        $this->user->expects($this->once())->method('__get')->with('username')->willReturn($userName);

        $this->setPrivateVariableValue($this->command, 'user', $this->userModel);

        $this->commandMessage->expects($this->once())->method('reply')->with('Ваша карма: 20')->willReturn($promise);

        $this->command->run($this->commandMessage, new ArrayObject(), false);
    }
}
