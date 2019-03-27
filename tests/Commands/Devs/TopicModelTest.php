<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Commands\Devs\Exceptions\TopicException;
use SoerBot\Commands\Devs\Implementations\TopicModel;
use SoerBot\Commands\Devs\Exceptions\TopicExceptionFileNotFound;

class TopicModelTest extends TestCase
{
    /** @var TopicModel $topic */
    private $topic;

    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Exceptions.
     */
    public function testConstructorThrowExceptionWhenFileNotExist()
    {
        $input = 'not_exist';
        $path = __DIR__ . '/testfiles/';
        $extension = '.topic.md';
        $file = $path . $input . $extension;

        $this->expectException(TopicExceptionFileNotFound::class);
        $this->expectExceptionMessage('File ' . $file . ' does not exists. Check file source.');

        $reflection = new \ReflectionClass(TopicModel::class);
        $topic = $reflection->newInstanceWithoutConstructor();
        $this->setPrivateVariableValue($topic, 'directory', $path);
        $topic->__construct($input);
    }

    public function testConstructorThrowExceptionWhenFileIsEmpty()
    {
        $input = 'empty';
        $path = __DIR__ . '/testfiles/';
        $extension = '.topic.md';
        $file = $path . $input . $extension;

        $this->expectException(TopicException::class);
        $this->expectExceptionMessage('File ' . $file . ' is empty. Check file source.');

        $reflection = new \ReflectionClass(TopicModel::class);
        $topic = $reflection->newInstanceWithoutConstructor();
        $this->setPrivateVariableValue($topic, 'directory', $path);
        $topic->__construct($input);
    }

    /**
     * Corner cases.
     */

    /**
     * Functionality.
     */
    public function testGetContentReturnExpected()
    {
        $input = 'second';
        $path = __DIR__ . '/testfiles/';

        $reflection = new \ReflectionClass(TopicModel::class);
        $topic = $reflection->newInstanceWithoutConstructor();
        $this->setPrivateVariableValue($topic, 'directory', $path);
        $topic->__construct($input);

        $this->assertSame('test file 2', $topic->getContent());
    }

    public function testIsTopicReturnTrueWhenRightFile()
    {
        $input = 'second';
        $path = __DIR__ . '/testfiles/';

        $reflection = new \ReflectionClass(TopicModel::class);
        $topic = $reflection->newInstanceWithoutConstructor();
        $this->setPrivateVariableValue($topic, 'directory', $path);
        $topic->__construct($input);
        $method = $this->getPrivateMethod($topic, 'isTopic');

        $this->assertTrue($method->invokeArgs($topic, [__DIR__ . '/testfiles/second.topic.md']));
    }

    public function testIsTopicReturnFalseWhenWrongFile()
    {
        $input = 'second';
        $path = __DIR__ . '/testfiles/';

        $reflection = new \ReflectionClass(TopicModel::class);
        $topic = $reflection->newInstanceWithoutConstructor();
        $this->setPrivateVariableValue($topic, 'directory', $path);
        $topic->__construct($input);
        $method = $this->getPrivateMethod($topic, 'isTopic');

        $this->assertFalse($method->invokeArgs($topic, [__DIR__ . '/testfiles/wrong_extension.md']));
    }
}
