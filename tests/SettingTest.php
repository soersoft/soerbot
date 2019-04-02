<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SoerBot\Settings;

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
