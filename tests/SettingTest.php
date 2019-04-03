<?php

namespace Tests;

use SoerBot\Settings;
use PHPUnit\Framework\TestCase;

class SettingTest extends TestCase
{
    /**
     * Exceptions.
     */

    /**
     * Corner cases.
     */

    /**
     * Functionality.
     */
    public function testConstructorIsNotAccessible()
    {
        $this->expectException(\Throwable::class);
        $this->expectExceptionMessageRegExp('/^Call to private.*/');

        new Settings();
    }

    public function testGetInstanceReturnSameInstance()
    {
        $instance1 = Settings::getInstance();
        $instance2 = Settings::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    public function testInitSetCommandNamespace()
    {
        $map = [
            ['name', 'devs'],
            ['description', 'some description'],
        ];
        $command = $this->createMock('\CharlotteDunois\Livia\Commands\Command');
        $command->method('__get')->will($this->returnValueMap($map));

        $settings = Settings::getInstance()->init($command, __DIR__ . '/Commands/Devs');
        $this->assertArrayHasKey('devs', $settings);
    }

    public function testInitSetCommandAttribute()
    {
        $map = [
            ['name', 'devs'],
            ['description', 'some description'],
        ];
        $command = $this->createMock('\CharlotteDunois\Livia\Commands\Command');
        $command->method('__get')->will($this->returnValueMap($map));

        $settings = Settings::getInstance()->init($command, __DIR__ . '/Commands/Devs');
        $this->assertContains('some description', $settings['devs']);
    }

    public function testInitSetCommandAddParametersFromFile()
    {
        $map = [
            ['name', 'devs'],
            ['description', 'some description'],
        ];
        $command = $this->createMock('\CharlotteDunois\Livia\Commands\Command');
        $command->method('__get')->will($this->returnValueMap($map));

        $settings = Settings::getInstance()->init($command, __DIR__ . '/Commands/Devs');
        $this->assertArrayHasKey('expected', $settings['devs']);
        $this->assertEquals('value', $settings['devs']['expected']);
    }

    public function testGetReturnExpectedWhenExistKey()
    {
        $expected = '/store/';

        $this->assertEquals($expected, Settings::getInstance()->get('store'));
    }

    public function testGetReturnNullWhenNotExistKey()
    {
        $this->assertNull(Settings::getInstance()->get('not_exist'));
    }

    public function testAllReturnExpectedType()
    {
        $settings = Settings::getInstance()->all();

        $this->assertIsArray($settings);
        $this->assertNotEmpty($settings);
    }
}
