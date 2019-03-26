<?php

namespace Tests\Commands;

use SoerBot\Commands\PhpFact\Abstractions\StorageInterface;
use SoerBot\Commands\PhpFact\Exceptions\PhpFactException;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use Tests\TestCase;

class PhpFactsTest extends TestCase
{
    private $facts;

    protected function setUp()
    {
        try {
            $storage = new FileStorage();
            $this->facts = new PhpFacts($storage);
        } catch (\Throwable $e) {
            $this->fail('Exception with ' . $e->getMessage() . ' was thrown is setUp method!');
        }

        parent::setUp();
    }

    /**
     * Exceptions
     */
    public function testThrowExceptionWhenStorageReturnEmpty()
    {
        $this->expectException(PhpFactException::class);

        $facts = new PhpFacts(new class() implements StorageInterface
        {
            public function get(): array
            {
                return [];
            }
        });
    }

    /**
     * Corner cases
     */


    /**
     * Functionality
     */
    public function testFetchReturnsAnArray()
    {
        $storage = new FileStorage();
        $method = $this->getPrivateMethod($this->facts, 'fetch');

        $this->assertIsArray($method->invokeArgs($this->facts, [$storage]));
    }

    public function testFetchWorksAsExpected()
    {
        $facts = $this->getPrivateVariableValue($this->facts, 'facts');

        $storage = $this->createMock(StorageInterface::class);
        $storage->expects($this->once())
            ->method('get')
            ->with()
            ->willReturn($facts);
        $method = $this->getPrivateMethod($this->facts, 'fetch');

        $this->assertIsArray($method->invokeArgs($this->facts, [$storage]));
    }

    public function testGetRandomReturnsStringFromStorageArray()
    {
        $allFacts = $this->getPrivateVariableValue($this->facts, 'facts');
        $getFact = $this->facts->getRandom();

        $this->assertTrue(in_array($getFact, $allFacts, true));
    }

    public function testGetRandomWorksAsExpected()
    {
        $facts = $this->getPrivateVariableValue($this->facts, 'facts');

        $storage = $this->createMock(StorageInterface::class);
        $storage->expects($this->once())
            ->method('get')
            ->with()
            ->willReturn($facts);

        new PhpFacts($storage);
    }
}
