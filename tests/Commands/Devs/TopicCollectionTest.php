<?php

namespace Tests\Commands;

use SoerBot\Commands\Devs\TopicCollection;
use SoerBot\Commands\Devs\TopicModel;
use Tests\TestCase;

class TopicCollectionTest extends TestCase
{
    /** @var TopicCollection $topic */
    private $collection;

    protected function setUp()
    {
        $this->collection = new TopicCollection(__DIR__ . '/testfiles');

        parent::setUp();
    }

    /*------------Exception block------------*/
    public function testConstructorThrowExceptionWhenDirectoryNotExist()
    {
        $path = __DIR__ . 'not_exist/';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('DevsCommand error: ' . $path . ' is not a valid directory.');

        new TopicCollection($path);
    }

    public function testConstructorThrowExceptionWhenDirectoryIsEmpty()
    {
        $path = __DIR__ . '/empty';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('DevsCommand error: directory ' . $path . ' does not contain topic files.');

        new TopicCollection($path);
    }

    /*------------Functional block------------*/
    public function testHasCollectionReturnExpected()
    {
        $firstIndex = 'first';

        $this->assertTrue($this->collection->has($firstIndex));
    }

    public function testGetListNamesShowReturnExpected()
    {
        $this->assertSame('first, second', $this->collection->listNames());
    }

    public function testGetListNamesShowWithList()
    {
        $topics = $this->getPrivateVariableValue($this->collection, 'topics');
        $topics += ['list' => ''];
        $this->setPrivateVariableValue($this->collection, 'topics', $topics);

        $this->assertSame('first, second, list - to list all command descriptions', $this->collection->listNames());
    }

    public function testGetOneReturnExistedTopicObjectByKey()
    {
        $existKey = 'first';

        $this->assertInstanceOf(TopicModel::class, $this->collection->getOne($existKey));
    }

    public function testGetOneReturnNullOnFalseKey()
    {
        $this->assertNull($this->collection->getOne('not_exist'));
    }

    public function testGetAllMakeRightObjects()
    {
        $firstIndex = 'first';

        $method = $this->getPrivateMethod($this->collection, 'getAll');

        $this->assertInstanceOf(TopicModel::class, $method->invoke($this->collection)[$firstIndex]);
    }

    public function testGetAllCanGetFiles()
    {
        $method = $this->getPrivateMethod($this->collection, 'getAll');

        $this->assertIsArray($method->invoke($this->collection));
        $this->assertCount(2, $method->invoke($this->collection));
    }
}