<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;

class QuizAddCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/Quiz/add.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(3))->method('has')->willReturn(true);
        $registry->expects($this->exactly(6))->method('__get')->with('types')->willReturn($types);
        $this->client->expects($this->exactly(6))->method('__get')->with('registry')->willReturn($registry);

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testQuizAddBasics()
    {
        $this->assertEquals($this->command->name, 'quiz-add');
        $this->assertEquals($this->command->description, 'Добавить вопрос в викторину');
        $this->assertEquals($this->command->groupID, 'games');
    }

    public function testQuizAddArguments()
    {
        $this->assertEquals(sizeof($this->command->args), 3);
        foreach ($this->command->args as $arg) {
            $this->assertArrayHasKey('key', $arg);
            $this->assertArrayHasKey('label', $arg);
            $this->assertArrayHasKey('prompt', $arg);
            $this->assertArrayHasKey('type', $arg);
        }
    }

    public function testSimpleResponseToTheDiscord(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $questionStore = $this->getMockBuilder('QuizStore')->setMethods(['add', 'save', 'load'])->getMock();

        $promise = new Promise(function () {
        });

        $commandMessage->expects($this->once())->method('say')->with('Вопрос добавлен')->willReturn($promise);
        $questionStore->expects($this->once())->method('load');
        $questionStore->expects($this->once())->method('add')->with(['Вопрос', 'Ответ', 'Теги'])->willReturn(true);
        $questionStore->method('save')->willReturn(true);

        // Подменяем store через reflection
        $reflection = new \ReflectionClass($this->command);
        $storeProperty = $reflection->getProperty('store');
        $storeProperty->setAccessible(true);
        $storeProperty->setValue($this->command, $questionStore);

        $this->command->run($commandMessage, new ArrayObject(['question' => 'Вопрос', 'answer' => 'Ответ', 'tags' => 'Теги']), false);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
