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

        $this->assertTrue($this->collection->hasTopic($firstIndex));
    }

    public function testGetTopicsNamesShowReturnExpected()
    {
        $this->assertSame('first, second', $this->collection->getTopicsNames());
    }

    public function testGetTopicsNamesShowWithList()
    {
        $topics = $this->getPrivateVariableValue($this->collection, 'topics');
        $topics += ['list' => ''];
        $this->setPrivateVariableValue($this->collection, 'topics', $topics);

        $this->assertSame('first, second, list - to list all command descriptions', $this->collection->getTopicsNames());
    }

    public function testGetTopicsMakeRightObjects()
    {
        $firstIndex = 'first';

        $method = $this->getPrivateMethod($this->collection, 'getTopics');

        $this->assertInstanceOf(TopicModel::class, $method->invoke($this->collection)[$firstIndex]);
    }

    public function testGetTopicsCanGetFiles()
    {
        $method = $this->getPrivateMethod($this->collection, 'getTopics');

        $this->assertIsArray($method->invoke($this->collection));
        $this->assertCount(2, $method->invoke($this->collection));
    }

    public function testGetTopicReturnNullOnFalseKey()
    {
        $method = $this->getPrivateMethod($this->collection, 'getTopic');
        $this->assertNull($method->invokeArgs($this->collection, ['not_exist']));
    }

    public function testGetTopicReturnExistedTopicObjectByKey()
    {
        $existKey = 'first';

        $method = $this->getPrivateMethod($this->collection, 'getTopic');
        $this->assertInstanceOf(TopicModel::class, $method->invokeArgs($this->collection, [$existKey]));
    }

    public function testGetContentReturnNullOnFalseKey()
    {
        $this->assertNull($this->collection->getContent('not_exist'));
    }

    public function testGetContentReturnReturnExpected()
    {
        $existKey = 'first';

        $this->assertSame('test file 1' . PHP_EOL, $this->collection->getContent($existKey));
    }
}