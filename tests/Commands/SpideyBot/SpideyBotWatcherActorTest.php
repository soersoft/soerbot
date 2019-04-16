<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Configurator;
use SoerBot\Commands\SpideyBot\WatcherActor\SpideyBotWatcherActor;

class SpideyBotWatcherActorTest extends TestCase
{
    protected function setUp()
    {
        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $this->watcher = new SpideyBotWatcherActor($this->client);

        parent::setUp();
    }

    public function testSuccessIsPassRequirements(): void
    {
        $pathToStubConfig = realpath(__DIR__ . '/../../Fixtures/config.stub.yaml');
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

        $this->assertEquals($this->watcher->isPassRequirements($message), true);
    }

    public function testFaildIsPassRequirements(): void
    {
        $pathToStubConfig = realpath(__DIR__ . '/../../Fixtures/config.stub.yaml');
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
            ['value' => '`master`]', 'name' => 'Branch'],
        ]);

        $this->assertEquals($this->watcher->isPassRequirements($message), false);
    }

    public function testRunMethod(): void
    {
        $message = $this->createMock('CharlotteDunois\Yasmin\Models\Message');

        $this->client->expects($this->at(0))->method('emit')->with('stop');
        $this->client->expects($this->at(1))->method('emit')->with('debug');

        $this->watcher->run($message);
    }
}
