<?php

namespace Tests\Commands;

use SoerBot\Commands\Devs\TopicModel;
use Tests\TestCase;

class TopicModelTest extends TestCase
{
    /** @var TopicModel $topic */
    private $topic;

    protected function setUp()
    {
        parent::setUp();
    }

    /*------------Exception block------------*/
    public function testConstructorThrowExceptionWhenFileNotExist()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('DevsCommand error. You must provide valid file path.');

        new TopicModel(__DIR__ . '/testfiles/not_exist.topic.md');
    }

    public function testConstructorThrowExceptionWhenWrongFileExtension()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('DevsCommand error. You must provide valid topic file.');

        new TopicModel(__DIR__ . '/testfiles/wrong_extension.md');
    }

    /*------------Functional block------------*/
    public function testGetPathReturnExpected()
    {
        $path = __DIR__ . '/testfiles/first.topic.md';
        $topic = new TopicModel($path);

        $this->assertSame($path, $topic->getPath());
    }

    public function testGetContentReturnExpected()
    {
        $topic = new TopicModel(__DIR__ . '/testfiles/second.topic.md');

        $this->assertSame("test file 2", $topic->getContent());
    }

    public function testIsTopicReturnTrue()
    {
        $this->assertTrue(TopicModel::isTopic(__DIR__ . '/testfiles/second.topic.md'));
    }

    /**
     * @dataProvider pathsProvider
     */
    public function testGetCleanNameReturnExpected($path, $expected)
    {
        $this->assertSame($expected, TopicModel::getCleanName($path));
    }

    public function pathsProvider()
    {
        return [
            [__DIR__ . '/test.topic.md', 'test'],
            ['/tmp/test/some.topic.md', 'some'],
            ['test.topic.md', 'test'],
        ];
    }
}