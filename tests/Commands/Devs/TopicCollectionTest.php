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
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('DevsCommand error. You must provide valid directory.');

        new TopicCollection(__DIR__ . 'not_exist/');
    }

    public function testConstructorThrowExceptionWhenDirectoryIsEmpty()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('DevsCommand error. You must provide directory with right files.');

        new TopicCollection(__DIR__ . '/empty');
    }

    /*------------Functional block------------*/
    public function testGetTopicsMakeRightObjects()
    {
        $firstIndex = 'first';

        $this->assertInstanceOf(TopicModel::class, $this->collection->getTopics()[$firstIndex]);
    }
    
    public function testGetTopicsCanGetFiles()
    {
        $this->assertIsArray($this->collection->getTopics());
        $this->assertCount(2, $this->collection->getTopics());
    }

    public function testGetNamesShowReturnExpected()
    {
        $this->assertSame('first, second', $this->collection->getNames());
    }

    public function testGetTopicReturnNullOnFalseKey()
    {
        $this->assertNull($this->collection->getTopic('not_exist'));
    }

    public function testGetTopicReturnExistedTopicObjectByKey()
    {
        $existKey = 'first';

        $this->assertInstanceOf(TopicModel::class, $this->collection->getTopic($existKey));
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