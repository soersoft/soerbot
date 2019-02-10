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

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testQuizBasics()
    {
        $this->assertEquals($this->command->name, 'quiz');
        $this->assertEquals($this->command->groupID, 'games');
    }

    public function testSimpleResponseToTheDiscord(): void
    {
        $result = ['question' => 'Вопрос', 'answer' => 'Ответ', 'tags' => 'Теги'];
        $expectedResult = 'Вопрос';

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });

        $commandMessage->expects($this->once())->method('say')->with($expectedResult)->willReturn($promise);

        $questionStore = $this->getMockBuilder('QuizStore')->setMethods(['get', 'load'])->getMock();
        $questionStore->expects($this->once())->method('get')->willReturn($result);

        // Подменяем store через reflection
        $reflection = new \ReflectionClass($this->command);
        $storeProperty = $reflection->getProperty('store');
        $storeProperty->setAccessible(true);
        $storeProperty->setValue($this->command, $questionStore);

        $this->command->run($commandMessage, new ArrayObject(), false);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
