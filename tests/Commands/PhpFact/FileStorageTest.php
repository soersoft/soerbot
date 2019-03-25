<?php

namespace Tests\Commands;

use SoerBot\Commands\PhpFact\Abstractions\StorageInterface;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use Tests\TestCase;

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

    /*------------Exception block------------*/
    public function testConstructorThrowExceptionWhenFileNotExist()
    {
        $file = 'not_exist';
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File ' . $file . ' does not exits.');

        new FileStorage($file);
    }

    public function testConstructorThrowExceptionWhenEmptyFile()
    {
        $file = __DIR__ . '/empty.txt';
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File ' . $file . ' was empty.');

        new FileStorage($file);
    }

    /*------------Corner case block------------*/

    /*------------Functional block------------*/
    public function testConstructorMakeInstanceWhichImplementRightInterface()
    {
        $this->assertInstanceOf(StorageInterface::class, $this->storage);
    }

    public function testConstructorMakeAnStorageArray()
    {
        $data = $this->getPrivateVariableValue($this->storage, 'data');
        $this->assertIsArray($data);
    }

    public function testFetchReturnsAnNonEmptyArray()
    {
        $facts = $this->storage->fetch();

        $this->assertIsArray($facts);
        $this->assertNotEmpty($facts);
    }
}
