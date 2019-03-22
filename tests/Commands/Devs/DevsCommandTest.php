<?php

namespace Tests\Commands;

use ArrayObject;
use SoerBot\Commands\Devs\DevsCommand;
use Tests\TestCase;
use React\Promise\Promise;

class DevsCommandTest extends TestCase
{
    /** @var DevsCommand $command */
    private $command;

    private $client;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/Devs/devs.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(1))->method('has')->willReturn(true);
        $registry->expects($this->exactly(2))->method('__get')->with('types')->willReturn($types);
        $this->client->expects($this->exactly(2))->method('__get')->with('registry')->willReturn($registry);

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    /** @test */
    public function throw_exception_on_wrong_directory()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('DevsCommand error. You must provide valid directory.');

        /* test private method trough Reflection */
        $reflectionClass = new \ReflectionClass(get_class($this->command));
        $reflectionMethod = $reflectionClass->getMethod('getTopics');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invokeArgs($this->command, ['xxx']);
    }

    /** @test */
    public function throw_exception_on_empty_directory()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('DevsCommand error. You must provide directory with right files.');

        /* test private method trough Reflection */
        $reflectionClass = new \ReflectionClass(get_class($this->command));
        $reflectionMethod = $reflectionClass->getMethod('getTopics');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invokeArgs($this->command, [__DIR__ . '/empty']);
    }

    /** @test */
    public function can_read_topics()
    {
        /* test private method trough Reflection */
        $reflectionClass = new \ReflectionClass(get_class($this->command));
        $reflectionMethod = $reflectionClass->getMethod('getTopics');
        $reflectionMethod->setAccessible(true);

        $this->assertSame(['beginner', 'how-to-start'], $reflectionMethod->invokeArgs($this->command, [__DIR__ . '/testfiles']));
    }

    /** @test */
    public function can_implode_topics()
    {
        /* test private method trough Reflection */
        $reflectionClass = new \ReflectionClass(get_class($this->command));
        $reflectionMethod = $reflectionClass->getMethod('stringifyTopics');
        $reflectionMethod->setAccessible(true);

        $this->assertSame('beginner, how-to-start', $reflectionMethod->invokeArgs($this->command, [['beginner', 'how-to-start']]));
    }

    /** @test */
    public function can_devs_command_work()
    {
        $this->assertEquals($this->command->name, 'devs');
        $this->assertEquals($this->command->description, 'Команда $devs выводит важные топики.');
        $this->assertEquals($this->command->groupID, 'utils');
    }

    /** @test */
    public function can_devs_command_get_arguments()
    {
        $this->assertEquals(sizeof($this->command->args), 1);
        $this->assertArrayHasKey('key', $this->command->args[0]);
        $this->assertArrayHasKey('label', $this->command->args[0]);
        $this->assertArrayHasKey('prompt', $this->command->args[0]);
        $this->assertArrayHasKey('type', $this->command->args[0]);

        $this->assertEquals($this->command->args[0]['key'], 'topic');
        $this->assertEquals($this->command->args[0]['label'], 'topic');
        $this->assertEquals($this->command->args[0]['prompt'], 'Укажите топик: how-to-start.');
        $this->assertEquals($this->command->args[0]['type'], 'string');
    }

    /** @test */
    public function can_devs_command_say_default_text()
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $commandMessage->expects($this->once())->method('say')->with('Укажите топик: how-to-start.')->willReturn($promise);
        $this->command->run($commandMessage, new ArrayObject(['topic' => '']), false);
    }

//    public function testHelpRulesArgument(): void
//    {
//        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
//        $promise = new Promise(function () {
//        });
//        $rulesContent = \file_get_contents(dirname(__FILE__) . '/../../commands/Devs/devs.topic/how-to-start.md');
//        $commandMessage->expects($this->once())->method('say')->with($rulesContent)->willReturn($promise);
//        $this->command->run($commandMessage, new ArrayObject(['topic' => 'rules']), false);
//    }
//
//    public function testHelpChannelArgument(): void
//    {
//        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
//        $promise = new Promise(function () {
//        });
//        $channelContent = \file_get_contents(dirname(__FILE__) . '/../../commands/Devs/devs.topic/beginner.md');
//        $commandMessage->expects($this->once())->method('say')->with($channelContent)->willReturn($promise);
//        $this->command->run($commandMessage, new ArrayObject(['topic' => 'channels']), false);
//    }

    // this hack used when test is faild and PHPUnit makes serialization of object properties
    public function __sleep()
    {
        $this->command = null;
    }
}
