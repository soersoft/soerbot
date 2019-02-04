<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use SoerBot\Configurator;
use React\Promise\Promise;

class WatchCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../commands/Watch/watch.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $this->client->expects($this->once())->method('on')->with('message');

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testWatchBasics()
    {
        $this->assertEquals($this->command->name, 'watch');
        $this->assertEquals($this->command->description, 'Check every message');
        $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testSimpleResponseToTheDiscord(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });

        $commandMessage->expects($this->once())->method('say')->with('...')->willReturn($promise);

        $this->command->run($commandMessage, new ArrayObject(), false);
    }

    public function testWatchMethod(): void
    {
        $pathToStubConfig = realpath(__DIR__ . '/../Fixtures/config.stub.yaml');
        Configurator::setConfigPath($pathToStubConfig);

        $message = $this->createMock('CharlotteDunois\Yasmin\Models\Message');
        $author = $this->createMock('CharlotteDunois\Yasmin\Models\User');
        $embed = $this->createMock('CharlotteDunois\Yasmin\Models\MessageEmbed');

        $message->expects($this->at(0))->method('__get')->with('author')->willReturn($author);
        $author->expects($this->once())->method('__get')->with('username')->willReturn('Spidey Bot');

        $message->expects($this->at(1))->method('__get')->with('embeds')->willReturn([$embed]);
        $message->expects($this->at(2))->method('__get')->with('embeds')->willReturn([$embed]);

        $embed->expects($this->at(0))->method('__get')->with('color')->willReturn(3066993);
        $embed->expects($this->at(1))->method('__get')->with('fields')->willReturn([
          ['value' => '[`6ca62d8`]', 'name' => 'Commit'],
          ['value' => '`develop`]', 'name' => 'Branch'],
      ]);

        $this->client->expects($this->at(0))->method('emit')->with('stop');
        $this->client->expects($this->at(1))->method('emit')->with('debug');

        $this->command->watch($message);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
