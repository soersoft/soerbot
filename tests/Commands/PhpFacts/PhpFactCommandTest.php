<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;

class PhpFactCommandTest extends TestCase
{

    private $command;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/PhpFact/phpfact.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testPhpFactBasics()
    {
       $this->assertEquals($this->command->name, 'phpfact');
       $this->assertEquals($this->command->description, 'Show PHP facts from pqr/5minphp-bot.');
       $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testSimpleResponseToTheDiscord(): void
    {

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () { });

        $commandMessage->expects($this->once())->method('say')->with($this->isType('string'))->willReturn($promise);
 
        $this->command->run($commandMessage, new ArrayObject(), false);
    }

    public function getPhpFact()
    {        
        $factsFile = __DIR__ . '/../../../commands/PhpFact/phpfact.txt';

        if (!file_exists($factsFile)) {
            $this->fail('Something wrong with PHP facts .txt file.');
        }

        $allFacst = @file($factsFile, FILE_IGNORE_NEW_LINES);
        
        if (empty($allFacst)) {
            $this->fail('Something wrong with PHP facts .txt file.');
        }
        
        return $allFacst;
    }

}