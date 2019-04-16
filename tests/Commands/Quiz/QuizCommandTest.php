<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;

class QuizCommandTest extends TestCase
{
    private $command;
    private $client;
    private $store;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/Quiz/quiz.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $this->client->expects($this->exactly(3))->method('on');
        $this->command = $commandCreate($this->client);

        // Подменяем store через reflection
        $this->store = $this->getMockBuilder('QuizStore')->setMethods(['add', 'get', 'save', 'load'])->getMock();
        $this->setPrivateVariableValue($this->command, 'store', $this->store);

        parent::setUp();
    }

    public function testQuizBasics()
    {
        $this->assertEquals($this->command->name, 'quiz');
        $this->assertEquals($this->command->groupID, 'games');
    }

    public function testSimpleResponseToTheDiscord(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $this->client->expects($this->once())->method('emit')->with('QuizStart');
        $this->command->run($commandMessage, new ArrayObject(), false);
    }

    public function testQuizStartAction()
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');

        $this->store->expects($this->once())->method('load');
        $this->store->expects($this->once())->method('get');
        $commandMessage->expects($this->once())->method('say')->with($this->isType('string'));

        $this
            ->getPrivateMethod($this->command, 'quizStartAction')
            ->invoke($this->command, $commandMessage);
    }

    public function testQuizNextAction()
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');

        $this->store->expects($this->once())->method('load');
        $this->store->expects($this->once())->method('get');
        $commandMessage->expects($this->once())->method('say')->with($this->isType('string'));

        $this
            ->getPrivateMethod($this->command, 'quizNextAction')
            ->invoke($this->command, $commandMessage);
    }

    public function testQuizEndAction()
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $commandMessage->expects($this->once())->method('say')->with($this->isType('string'));

        $this
            ->getPrivateMethod($this->command, 'quizEndAction')
            ->invoke($this->command, $commandMessage);
    }

    public function __sleep()
    {
        $this->command = null;
        $this->client = null;
    }
}
