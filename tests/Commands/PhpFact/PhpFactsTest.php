<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Exceptions\PhpFactException;
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
    public function testThrowExceptionWhenStorageReturnEmpty()
    {
        $this->expectException(PhpFactException::class);

        $facts = new PhpFacts(new class() implements StorageInterface {
            public function get(): array
            {
                return [];
            }
        });
    }

    /**
     * Corner cases.
     */

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

    public function testGetRandomReturnStringFromStorage()
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
