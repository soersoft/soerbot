<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;
use SoerBot\Commands\Devs\DevsCommand;

class DevsCommandMockeryTest extends TestCase
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

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testRunSayRightTextWhenTopicExist()
    {
        $input = 'first';
        $expected = 'test file 1' . PHP_EOL;

        $external = \Mockery::mock('overload:SoerBot\Commands\Devs\Implementations\TopicModel');
        $external->shouldReceive('__construct')
                ->once()
                ->with($input);
        $external->shouldReceive('getContent')
                ->once()
                ->andReturn($expected);

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $commandMessage->expects($this->once())->method('direct')->with($expected)->willReturn($promise);

        $this->command->run($commandMessage, new ArrayObject(['topic' => $input]), false, $external);
    }

    // this hack used when test is faild and PHPUnit makes serialization of object properties
    public function __sleep()
    {
        $this->command = null;
    }
}
