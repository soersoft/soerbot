<?php

namespace Tests\Commands;

use SoerBot\Commands\PhpFact\Abstractions\StorageInterface;
use SoerBot\Commands\PhpFact\Exceptions\PhpFactException;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use SoerBot\Commands\PhpFact\Implementations\PhpFact;
use Tests\TestCase;

class PhpFactTest extends TestCase
{
    private $fact;

    protected function setUp()
    {
        try {
            $storage = new FileStorage();
            $this->fact = new PhpFact($storage);
        } catch (\Throwable $e) {
            $this->fail('Exception with ' . $e->getMessage() . ' was thrown is setUp method!');
        }

        parent::setUp();
    }

    /*------------Exception block------------*/
    public function testThrowExceptionWhenStorageReturnEmpty()
    {
        $this->expectException(PhpFactException::class);

        $facts = new PhpFact(new class() implements StorageInterface
        {
            public function fetch(): array
            {
                return [];
            }
        });
    }

    /*------------Corner case block------------*/

    /*------------Functional block------------*/
    public function testGetWorksAsExpected()
    {
        $facts = $this->getPrivateVariableValue($this->fact, 'facts');

        $storage = $this->createMock(StorageInterface::class);
        $storage->expects($this->once())
                ->method('fetch')
                ->with()
                ->willReturn($facts);

        new PhpFact($storage);
    }

    public function testGetReturnsStringFromStorageArray()
    {
        $allFacts = $this->getPrivateVariableValue($this->fact, 'facts');
        $getFact = $this->fact->get();

        $this->assertTrue(in_array($getFact, $allFacts, true));
    }
}
