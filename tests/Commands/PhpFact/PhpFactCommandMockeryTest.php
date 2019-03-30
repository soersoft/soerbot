<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use SoerBot\Commands\PhpFact\PhpFactCommand;
use SoerBot\Commands\PhpFact\Exceptions\PhpFactException;
use SoerBot\Commands\PhpFact\Exceptions\StorageException;
use SoerBot\Commands\PhpFact\Implementations\CommandHelper;

class PhpFactCommandMockeryTest extends TestCase
{
    /** @var PhpFactCommand $command */
    private $command;

    private $client;

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
    public function testRunSayErrorTextWhenSomethingWentWrongInPhpFactsClass()
    {
        $input = 'fact';

        $external = \Mockery::mock('overload:SoerBot\Commands\PhpFact\Implementations\PhpFacts');
        $external->shouldReceive('__construct')
                ->once()
                ->andThrow(new PhpFactException('Facts array is empty.'));

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $commandMessage->expects($this->once())->method('say')->with(CommandHelper::getCommandErrorMessage());

        $this->command->run($commandMessage, new ArrayObject(['command' => $input]), false);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testRunSayErrorTextWhenSomethingWentWrongInFileStorageClass()
    {
        $input = 'fact';

        $storage = \Mockery::mock('overload:SoerBot\Commands\PhpFact\Implementations\FileStorage');
        $storage->shouldReceive('__construct')
                ->once()
                ->andThrow(new StorageException('Check source file.'));

        $external = \Mockery::mock('overload:SoerBot\Commands\PhpFact\Implementations\PhpFacts');
        $external->shouldNotReceive('__construct');

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $commandMessage->expects($this->once())->method('say')->with(CommandHelper::getCommandErrorMessage());

        $this->command->run($commandMessage, new ArrayObject(['command' => $input]), false);
    }

    // this hack used when test is faild and PHPUnit makes serialization of object properties
    public function __sleep()
    {
        $this->command = null;
    }
}
