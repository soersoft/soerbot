<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use SoerBot\Commands\PhpFact\PhpFactCommand;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;

class PhpFactCommandTest extends TestCase
{
    /**
     * @var PhpFactCommand
     */
    private $command;

    /**
     * Default client prompt and run method message.
     *
     * @var string
     */
    private $message;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/PhpFact/phpfact.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(1))->method('has')->willReturn(true);
        $registry->expects($this->exactly(2))->method('__get')->with('types')->willReturn($types);
        $this->client->expects($this->exactly(2))->method('__get')->with('registry')->willReturn($registry);

        $this->command = $commandCreate($this->client);

        $message = $this->getPrivateMethod($this->command, 'getDefaultMessage');
        $this->message = $message->invoke($this->command);

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
    public function testConstructorMakeRightObject()
    {
        $this->assertEquals($this->command->name, 'phpfact');
        $this->assertEquals($this->command->description, 'Show PHP facts from https://github.com/pqr/5minphp-bot.');
        $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testConstructorMakeRightObjectWithDefaultArguments()
    {
        $this->assertEquals(count($this->command->args), 1);
        $this->assertArrayHasKey('key', $this->command->args[0]);
        $this->assertArrayHasKey('label', $this->command->args[0]);
        $this->assertArrayHasKey('prompt', $this->command->args[0]);
        $this->assertArrayHasKey('type', $this->command->args[0]);

        $this->assertEquals($this->command->args[0]['key'], 'command');
        $this->assertEquals($this->command->args[0]['label'], 'command');
        $this->assertEquals($this->command->args[0]['prompt'], $this->message);
        $this->assertEquals($this->command->args[0]['type'], 'string');
    }

    public function testRunSayDefaultTextWhenEmptyCommand()
    {
        try {
            $storage = new FileStorage();
            $factObject = new PhpFacts($storage);
        } catch (\Throwable $e) {
            $this->fail('Exception with ' . $e->getMessage() . ' was thrown is test method!');
        }

        $facts = $this->getPrivateVariableValue($factObject, 'facts');

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $commandMessage->expects($this->once())
                        ->method('say')
                        ->with(
                            $this->message
                        );

        $this->command->run($commandMessage, new ArrayObject(['command' => '']), false);
    }

    public function testRunSayOneOfFactWhenFactCommand()
    {
        try {
            $storage = new FileStorage();
            $factObject = new PhpFacts($storage);
        } catch (\Throwable $e) {
            $this->fail('Exception with ' . $e->getMessage() . ' was thrown is test method!');
        }

        $facts = $this->getPrivateVariableValue($factObject, 'facts');

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $commandMessage->expects($this->once())
                        ->method('say')
                        ->with(
                            $this->logicalAnd(
                                $this->isType('string'),
                                $this->callback(
                                    function ($parameter) use ($facts) {
                                        return in_array($parameter, $facts);
                                    }
                                )
                            )
                        );

        $this->command->run($commandMessage, new ArrayObject(['command' => 'fact']), false);
    }

    public function testRunSayOneOfFactWhenCountCommand()
    {
        try {
            $storage = new FileStorage();
            $factObject = new PhpFacts($storage);
        } catch (\Throwable $e) {
            $this->fail('Exception with ' . $e->getMessage() . ' was thrown is test method!');
        }

        $facts = $this->getPrivateVariableValue($factObject, 'facts');

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $commandMessage->expects($this->once())
                        ->method('say')
                        ->with(
                            'We have 43 facts in collection.'
                        );

        $this->command->run($commandMessage, new ArrayObject(['command' => 'stat']), false);
    }
}
