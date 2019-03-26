<?php

namespace Tests\Commands;

use ArrayObject;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\PhpFactCommand;
use Tests\TestCase;
use React\Promise\Promise;

class PhpFactCommandTest extends TestCase
{

    /**
     * @var PhpFactCommand
     */
    private $command;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/PhpFact/phpfact.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    /**
     * Exceptions
     */

    /**
     * Corner cases
     */

    /**
     * Functionality
     */

    public function testPhpFactBasics()
    {
        $this->assertEquals($this->command->name, 'phpfact');
        $this->assertEquals($this->command->description, 'Show PHP facts from https://github.com/pqr/5minphp-bot.');
        $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testSimpleResponseToTheDiscordContainsFact()
    {
        try {
            $storage = new FileStorage();
            $factObject = new PhpFacts($storage);
        } catch (\Throwable $e) {
            $this->fail('Exception with ' . $e->getMessage() . ' was thrown is test method!');
        }

        $facts = $this->getPrivateVariableValue($factObject, 'facts');

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
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
                        )
                        ->willReturn($promise);
 
        $this->command->run($commandMessage, new ArrayObject(), false);
    }
}