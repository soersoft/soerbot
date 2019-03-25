<?php

namespace Tests\Commands;

use SoerBot\Commands\PhpFact\Implementations\Storage;
use Tests\TestCase;

class StorageTest extends TestCase
{
    protected $storage;

    protected function setUp()
    {
        try {
            $this->storage = new Storage();
        } catch (\Exception $e) {
            $this->fail('Exception with ' . $e->getMessage() . 'was thrown is setUp method!');
        }

        parent::setUp();
    }

    /*------------Exception block------------*/
    public function testConstructorThrowExceptionWhenFileNotExist()
    {
        $file = 'not_exist';
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File ' . $file . ' does not exits.');

        new Storage($file);
    }

    public function testConstructorThrowExceptionWhenEmptyFile()
    {
        $file = __DIR__ . '/empty.txt';
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File ' . $file . ' was empty.');

        new Storage($file);
    }

    /*------------Corner case block------------*/

    /*------------Functional block------------*/
    public function testConstructorMadeAnStorageArray()
    {
        $data = $this->getPrivateVariableValue($this->storage, 'data');
        $this->assertIsArray($data);
    }

    public function testFetchReturnsAnArray()
    {
        $this->assertIsArray($this->storage->fetch());
    }
}
