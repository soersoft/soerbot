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
        $file = __DIR__ . '/testfiles/not_exist.topic.md';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('DevsCommand error: file ' . $file . ' does not exists.');

        TopicModel::create($file);
    }

    public function testConstructorThrowExceptionWhenWrongFileExtension()
    {
        $file = __DIR__ . '/testfiles/wrong_extension.md';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('DevsCommand error: file ' . $file . ' has wrong extension and is not valid topic file.');

        TopicModel::create($file);
    }

    /*------------Functional block------------*/
    public function testGetPathReturnExpected()
    {
        $path = __DIR__ . '/testfiles/first.topic.md';
        $topic = TopicModel::create($path);

        $this->assertSame($path, reset($topic)->getPath());
    }

    public function testGetContentReturnExpected()
    {
        $topic = TopicModel::create(__DIR__ . '/testfiles/second.topic.md');

        $this->assertSame("test file 2", reset($topic)->getContent());
    }

    public function testGetContentReturnNullWhenWrongFile()
    {
        $topic = TopicModel::create(__DIR__ . '/testfiles/second.topic.md');
        $object = reset($topic);
        $this->setPrivateVariableValue($object, 'filePath', 'not_exist');

        $this->assertNull($object->getContent());
    }

    public function testIsTopicReturnTrue()
    {
        $object = TopicModel::create(__DIR__ . '/testfiles/second.topic.md');
        $method = $this->getPrivateMethod(reset($object), 'isTopic');

        $this->assertTrue($method->invokeArgs(null, [__DIR__ . '/testfiles/second.topic.md']));
    }

    /**
     * @dataProvider pathsProvider
     */
    public function testGetCleanNameReturnExpected($path, $expected)
    {
        $object = TopicModel::create(__DIR__ . '/testfiles/second.topic.md');
        $method = $this->getPrivateMethod(reset($object), 'getKey');

        $this->assertSame($expected, $method->invokeArgs(null, [$path]));
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