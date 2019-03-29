<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use SoerBot\Commands\PhpFact\Abstractions\StorageInterface;

class PhpFactsTest extends TestCase
{
    private $facts;

    protected function setUp()
    {
        try {
            $file = __DIR__ . '/phpfacts.txt';
            $storage = new FileStorage($file);
            $this->facts = new PhpFacts($storage);
        } catch (\Throwable $e) {
            $this->fail('Exception with ' . $e->getMessage() . ' was thrown is setUp method!');
        }

        parent::setUp();
    }

    /**
     * Exceptions.
     */

    /**
     * Corner cases.
     */

    /**
     * @dataProvider providePositionCorners
     */
    public function testHasPositionReturnExpected($position, $expected)
    {
        $method = $this->getPrivateMethod($this->facts, 'hasPosition');

        $this->assertSame($expected, $method->invokeArgs($this->facts, [$position]));
    }

    public function providePositionCorners()
    {
        $file = __DIR__ . '/phpfacts.txt';
        $storage = new FileStorage($file);
        $facts = new PhpFacts($storage);

        $countFacts = count($this->getPrivateVariableValue($facts, 'facts'));

        return [
            'below minimum' => [-1, false],
            'array start' => [0, true],
            'array before stop' => [$countFacts - 2, true],
            'array stop' => [$countFacts - 1, true],
            'above maximum' => [$countFacts, false],
        ];
    }

    /**
     * @dataProvider provideFactsContentCorners
     */
    public function testGetReturnExpected($position, $expected)
    {
        $this->assertSame($expected, $this->facts->get($position));
    }

    public function provideFactsContentCorners()
    {
        $file = __DIR__ . '/phpfacts.txt';
        $storage = new FileStorage($file);
        $facts = new PhpFacts($storage);

        $countFacts = count($this->getPrivateVariableValue($facts, 'facts'));
        $content = $this->getPrivateVariableValue($facts, 'facts');

        return [
            'below minimum' => [0, false],
            'array start' => [1, $content[0]],
            'array before stop' => [$countFacts - 1, $content[$countFacts - 2]],
            'array stop' => [$countFacts, $content[$countFacts - 1]],
            'above maximum' => [$countFacts + 1, false],
        ];
    }

    /**
     * Functionality.
     */
    public function testLoadReturnArray()
    {
        $storage = new FileStorage();
        $method = $this->getPrivateMethod($this->facts, 'load');

        $this->assertIsArray($method->invokeArgs($this->facts, [$storage]));
    }

    public function testLoadReturnSameArrayAsFacts()
    {
        $facts = $this->getPrivateVariableValue($this->facts, 'facts');

        $storage = $this->createMock(StorageInterface::class);
        $storage->expects($this->once())
            ->method('get')
            ->with()
            ->willReturn($facts);
        $method = $this->getPrivateMethod($this->facts, 'load');

        $this->assertIsArray($method->invokeArgs($this->facts, [$storage]));
    }

    public function testGetReturnStringFromFacts()
    {
        $allFacts = $this->getPrivateVariableValue($this->facts, 'facts');

        $this->assertSame($allFacts[0], $this->facts->get(1));
    }

    public function testGetRandomReturnStringFromFacts()
    {
        $allFacts = $this->getPrivateVariableValue($this->facts, 'facts');
        $getFact = $this->facts->getRandom();

        $this->assertTrue(in_array($getFact, $allFacts, true));
    }

    public function testGetRandomReturnExpectedString()
    {
        $facts = $this->getPrivateVariableValue($this->facts, 'facts');

        $storage = $this->createMock(StorageInterface::class);
        $storage->expects($this->once())
            ->method('get')
            ->with()
            ->willReturn($facts);

        new PhpFacts($storage);
    }

    public function testCountReturnExpectedCount()
    {
        $this->assertSame(5, $this->facts->count());
    }
}
