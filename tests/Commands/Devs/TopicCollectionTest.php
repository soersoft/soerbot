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

    public function testGetNamesShowReturnExpected()
    {
        $this->assertSame('first, second', $this->collection->getNames());
    }

    public function testGetTopicsMakeRightObjects()
    {
        $firstIndex = 'first';

        $method = $this->getPrivateMethod($this->collection, 'getTopics');

        $this->assertInstanceOf(TopicModel::class, $method->invoke($this->collection)[$firstIndex]);
    }

    public function testGetTopicsCanGetFiles()
    {
        $this->assertIsArray($this->collection->getTopics());
        $this->assertCount(2, $this->collection->getTopics());
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