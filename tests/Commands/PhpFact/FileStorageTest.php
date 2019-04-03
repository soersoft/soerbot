<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use SoerBot\Commands\PhpFact\Abstractions\StorageInterface;

class FileStorageTest extends TestCase
{
    protected $storage;

    protected function setUp()
    {
        try {
            $this->storage = new FileStorage();
        } catch (\Exception $e) {
            $this->fail('Exception with ' . $e->getMessage() . ' was thrown is setUp method!');
        }

        parent::setUp();
    }

    /**
     * Exceptions.
     */
    public function testConstructorThrowExceptionWhenFileNotExist()
    {
        $file = 'not_exist';
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File ' . $file . ' does not exists. Check source file.');

        new FileStorage($file);
    }

    public function testConstructorThrowExceptionWhenEmptyFile()
    {
        $file = __DIR__ . '/empty.txt';
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File ' . $file . ' is empty. Check source file.');

        new FileStorage($file);
    }

    /**
     * Corner cases.
     */

    /**
     * Functionality.
     */
    public function testConstructorMakeInstanceImplementRightInterface()
    {
        $this->assertInstanceOf(StorageInterface::class, $this->storage);
    }

    public function testConstructorMakeArrayWhenStart()
    {
        $data = $this->getPrivateVariableValue($this->storage, 'data');
        $this->assertIsArray($data);
    }

    public function testGetReturnNotEmptyArray()
    {
        $facts = $this->storage->get();

        $this->assertIsArray($facts);
        $this->assertNotEmpty($facts);
    }

    public function testGetReturnExpected()
    {
        $file = __DIR__ . '/phpfacts.txt';
        $storage = new FileStorage($file);
        $facts = $storage->get();

        $this->assertIsArray($facts);
        $this->assertCount(5, $facts);
    }
}
