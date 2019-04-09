<?php

namespace Tests\Commands\Karma;

use Tests\TestCase;
use SoerBot\Commands\Karma\Implementations\KarmaStoreJSONFile;

class KarmaStoreTest extends TestCase
{
    private $store;

    protected function setUp()
    {
        $this->store = new KarmaStoreJSONFile();

        parent::setUp();
    }

    public function testAddFunctionWithEmptyStore()
    {
        $this->setPrivateVariableValue($this->store, 'data', []);
        $this->store->add(['name' => 'username', 'karma' => 3]);
        $result = $this->getPrivateVariableValue($this->store, 'data');
        $this->assertEquals($result, [['name' => 'username', 'karma' => 3]]);
    }

    public function testAddFunctionWithNotEmptyStore()
    {
        $this->setPrivateVariableValue($this->store, 'data', [['name' => 'username', 'karma' => 0]]);
        $this->store->add(['name' => 'username', 'karma' => 1]);
        $result = $this->getPrivateVariableValue($this->store, 'data');
        $this->assertEquals($result, [['name' => 'username', 'karma' => 1]]);
    }

    public function testGetFunctionWithEmptyStore()
    {
        $this->setPrivateVariableValue($this->store, 'data', []);
        $this->assertEquals($this->store->get('username'), []);
    }

    public function testGetFunctionWithNotEmptyStore()
    {
        $this->setPrivateVariableValue($this->store, 'data', [['name' => 'username', 'karma' => 3]]);
        $this->assertEquals($this->store->get('username'), ['name' => 'username', 'karma' => 3]);
    }

    public function testLoadFunction()
    {
        $this->setPrivateVariableValue($this->store, 'file', __DIR__ . '/../../Fixtures/karma.json');
        $this->store->load();

        $result = $this->getPrivateVariableValue($this->store, 'data');
        $this->assertEquals($result, [
            ['name' => 'username', 'karma' => 0],
            ['name' => 'username1', 'karma' => 1],
            ['name' => 'username2', 'karma' => 2],
        ]);
    }

    public function testSaveFunction()
    {
        $filePath = __DIR__ . '/../../Fixtures/karma.tmp.json';
        $expectedResult = [['name' => 'username', 'karma' => 1]];

        $this->setPrivateVariableValue($this->store, 'file', $filePath);
        $this->setPrivateVariableValue($this->store, 'data', $expectedResult);
        $this->store->save();

        $result = json_decode(file_get_contents($filePath), true);
        $this->assertEquals($result, $expectedResult);
    }

    public function testCreateStoreFilFunction()
    {
        $filePath = __DIR__ . '/../../Fixtures/karma.create-store.tmp.json';
        $this->setPrivateVariableValue($this->store, 'file', $filePath);

        $this->store->createStoreFile();
        $this->assertEquals(file_exists($filePath), true);
    }
}
