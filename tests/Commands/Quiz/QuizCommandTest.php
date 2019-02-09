<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;

class QuizCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/Quiz/quiz.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(1))->method('has')->willReturn(true);
        $registry->expects($this->exactly(2))->method('__get')->with('types')->willReturn($types);
        $this->client->expects($this->exactly(2))->method('__get')->with('registry')->willReturn($registry);

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testQuizBasics()
    {
        $this->assertEquals($this->command->name, 'quiz');
        $this->assertEquals($this->command->groupID, 'games');
    }

    public function testQuizArguments()
    {
        $this->assertEquals(sizeof($this->command->args), 1);
        $this->assertArrayHasKey('key', $this->command->args[0]);
        $this->assertArrayHasKey('label', $this->command->args[0]);
        $this->assertArrayHasKey('prompt', $this->command->args[0]);
        $this->assertArrayHasKey('type', $this->command->args[0]);
        $this->assertArrayHasKey('validate', $this->command->args[0]);

        $this->assertEquals($this->command->args[0]['key'], 'action');
        $this->assertEquals($this->command->args[0]['label'], 'action');
        $this->assertEquals($this->command->args[0]['prompt'], 'quiz [start|add]');
        $this->assertEquals($this->command->args[0]['type'], 'string');
    }

    public function testValidateFunctionShouldPassOnlyDefinedActions()
    {
        $this->assertTrue($this->command->args[0]['validate']('start'));
        $this->assertTrue($this->command->args[0]['validate']('add'));
        $this->assertTrue($this->command->args[0]['validate']('help'));

        $this->assertFalse($this->command->args[0]['validate']('anotherCommand'));
    }

    public function testStartAction()
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $commandMessage->expects($this->once())->method('say')->with('Давайте сыграем')->willReturn($promise);

        $this->command->run($commandMessage, new ArrayObject(['action' => 'start']), false);
    }

    public function testAddAction()
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $commandMessage->expects($this->once())->method('say')->with('Добавьте новый вопрос')->willReturn($promise);

        $this->command->run($commandMessage, new ArrayObject(['action' => 'add']), false);
    }

    public function testSimpleResponseToTheDiscord(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });

        $commandMessage->expects($this->once())->method('say')->with('quiz [start|add]')->willReturn($promise);

        $this->command->run($commandMessage, new ArrayObject(), false);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
