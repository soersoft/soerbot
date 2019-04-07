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
    public function testSearchThrowExceptionWhenEmptyInput()
    {
        $this->expectException(PhpFactException::class);
        $this->expectExceptionMessage('Passed pattern is empty.');

        $this->facts->search('');
    }

    public function testSearchThrowExceptionWhenPatternIsLessThanMinLength()
    {
        $this->expectException(PhpFactException::class);
        $this->expectExceptionMessage('Passed pattern is less than minimum ' . PhpFacts::SEARCH_MIN_LENGTH . ' chars.');

        $this->facts->search(str_repeat('t', PhpFacts::SEARCH_MIN_LENGTH - 1));
    }

    public function testSearchThrowExceptionWhenPatternIsMoreThanMaxLength()
    {
        $this->expectException(PhpFactException::class);
        $this->expectExceptionMessage('Passed pattern is more than maximum ' . PhpFacts::SEARCH_MAX_LENGTH . ' chars.');

        $this->facts->search(str_repeat('t', PhpFacts::SEARCH_MAX_LENGTH + 1));
    }

    /**
     * Corner cases.
     */
    public function testSearchDontThrowExceptionWhenPatternIsMin()
    {
        try {
            $this->facts->search(str_repeat('t', PhpFacts::SEARCH_MIN_LENGTH));
        } catch (PhpFactException $e) {
            $this->fail('Exception thrown on min plus one with message ' . $e->getMessage() . '');
        }

        $this->assertTrue(true);
    }

    public function testSearchDontThrowExceptionWhenPatternIsMax()
    {
        try {
            $this->facts->search(str_repeat('t', PhpFacts::SEARCH_MAX_LENGTH));
        } catch (PhpFactException $e) {
            $this->fail('Exception thrown on min plus one with message ' . $e->getMessage() . '');
        }

        $this->assertTrue(true);
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

    public function testSearchFindNothingWhenNotExistPattern()
    {
        $this->assertEmpty($this->facts->search('not_exist'));
    }

    public function testSearchFindOneWhenOneExistPattern()
    {
        $result = $this->facts->search('yield');

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
    }

    public function testSearchFindTwoWhenTwoExistPattern()
    {
        $result = $this->facts->search('ооп');

        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
    }

    public function testSearchFindThreeWhenThreeExistPattern()
    {
        $result = $this->facts->search('java');

        $this->assertNotEmpty($result);
        $this->assertCount(3, $result);
    }

    public function testSearchFindOneWhenOneWithSpaceExistPattern()
    {
        $result = $this->facts->search('PHP 5');

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
    }

    public function testSearchFindOneWhenThreeInsideWordExistPattern()
    {
        $result = $this->facts->search('orm');

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
    }

    public function testSearchFindNothingWhenOneInsideWordExistPattern()
    {
        $this->assertEmpty($this->facts->search('octrin'));
    }

    public function testCountReturnExpectedCount()
    {
        $this->assertSame(9, $this->facts->count());
    }
}
