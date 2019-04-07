<?php

namespace Tests\Commands\PhpFact\Commands;

use Tests\TestCase;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use SoerBot\Commands\PhpFact\Exceptions\CommandWrongUsageException;
use SoerBot\Commands\PhpFact\Implementations\Commands\SearchCommand;

class SearchCommandTest extends TestCase
{
    private $facts;

    public function setUp()
    {
        try {
            $file = __DIR__ . '/../phpfacts.txt';
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
    public function testConstructorThrowExceptionWhenArgumentNotSet()
    {
        $facts = $this->createMock(PhpFacts::class);

        $this->expectException(CommandWrongUsageException::class);
        $this->expectExceptionMessage('Wrong usage of search command. Check if you pass argument.');

        new SearchCommand($facts, []);
    }

    public function testConstructorThrowExceptionWhenPatternArgumentNotString()
    {
        $facts = $this->createMock(PhpFacts::class);

        $this->expectException(CommandWrongUsageException::class);
        $this->expectExceptionMessage('Wrong usage of search command. Check if argument is empty.');

        new SearchCommand($facts, ['argument' => '    ']);
    }

    public function testConstructorThrowExceptionWhenPatternIsLessThanMinLength()
    {
        $facts = $this->createMock(PhpFacts::class);

        $this->expectException(CommandWrongUsageException::class);
        $this->expectExceptionMessage('Wrong usage of search command. Argument is less than minimum ' . SearchCommand::MIN_LENGTH . ' chars.');

        new SearchCommand($facts, ['argument' => str_repeat('t', SearchCommand::MIN_LENGTH - 1)]);
    }

    public function testConstructorThrowExceptionWhenPatternIsMoreThanMaxLength()
    {
        $facts = $this->createMock(PhpFacts::class);

        $this->expectException(CommandWrongUsageException::class);
        $this->expectExceptionMessage('Wrong usage of search command. Argument is more than maximum ' . SearchCommand::MAX_LENGTH . ' chars.');

        new SearchCommand($facts, ['argument' => str_repeat('t', SearchCommand::MAX_LENGTH + 1)]);
    }

    /**
     * Corner cases.
     */
    public function testConstructorDontThrowExceptionWhenPatternIsMin()
    {
        $facts = $this->createMock(PhpFacts::class);

        try {
            new SearchCommand($facts, ['argument' => str_repeat('t', SearchCommand::MIN_LENGTH)]);
        } catch (CommandWrongUsageException $e) {
            $this->fail('Exception thrown on min plus one with message ' . $e->getMessage() . '');
        }

        $this->assertTrue(true);
    }

    public function testConstructorDontThrowExceptionWhenPatternIsMax()
    {
        $facts = $this->createMock(PhpFacts::class);

        try {
            new SearchCommand($facts, ['argument' => str_repeat('t', SearchCommand::MAX_LENGTH)]);
        } catch (CommandWrongUsageException $e) {
            $this->fail('Exception thrown on min plus one with message ' . $e->getMessage() . '');
        }

        $this->assertTrue(true);
    }

    /**
     * Functionality.
     */
    public function testResponseWithArgumentsReturnString()
    {
        $command = new SearchCommand($this->facts, ['argument' => 'test']);

        $this->assertIsString($command->response());
    }

    public function testResponseWithArgumentsSetPatternWithoutSpace()
    {
        $expected = 'string';
        $command = new SearchCommand($this->facts, ['argument' => $expected]);

        $content = $this->getPrivateVariableValue($command, 'pattern');

        $this->assertEquals($expected, $content);
    }

    public function testResponseWithArgumentsSetPatternWithSpace()
    {
        $expected = 'some string';
        $command = new SearchCommand($this->facts, ['argument' => $expected]);

        $content = $this->getPrivateVariableValue($command, 'pattern');

        $this->assertEquals($expected, $content);
    }

    public function testSearchFindNothingWhenNotExistPattern()
    {
        $expected = 'not_exist';
        $command = new SearchCommand($this->facts, ['argument' => $expected]);

        $found = $this->getPrivateVariableValue($command, 'found');

        $this->assertEmpty($found);
    }

    public function testSearchFindOneWhenOneExistPattern()
    {
        $expected = 'yield';
        $command = new SearchCommand($this->facts, ['argument' => $expected]);

        $found = $this->getPrivateVariableValue($command, 'found');

        $this->assertNotEmpty($found);
        $this->assertCount(1, $found);
    }

    public function testSearchFindTwoWhenTwoExistPattern()
    {
        $expected = 'ооп';
        $command = new SearchCommand($this->facts, ['argument' => $expected]);

        $found = $this->getPrivateVariableValue($command, 'found');

        $this->assertNotEmpty($found);
        $this->assertCount(2, $found);
    }

    public function testSearchFindThreeWhenThreeExistPattern()
    {
        $expected = 'java';
        $command = new SearchCommand($this->facts, ['argument' => $expected]);

        $found = $this->getPrivateVariableValue($command, 'found');

        $this->assertNotEmpty($found);
        $this->assertCount(3, $found);
    }

    public function testResponseReturnNothingWhenNotExistPattern()
    {
        $expected = 'not_exist';
        $command = new SearchCommand($this->facts, ['argument' => $expected]);

        $this->assertEquals('Nothing found on ' . $expected . ' request', $command->response());
    }

    public function testResponseReturnExpectedOneWhenOneExistPattern()
    {
        $pattern = 'yield';
        $command = new SearchCommand($this->facts, ['argument' => $pattern]);

        $found = $this->facts->search($pattern);

        $this->assertEquals($found[0], $command->response());
    }

    public function testResponseReturnExpectedTwoWhenTwoExistPattern()
    {
        $result = $this->facts->search('ооп');

        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
    }

    public function testResponseReturnExpectedThreeWhenThtreeExistPattern()
    {
        $pattern = 'java';
        $command = new SearchCommand($this->facts, ['argument' => $pattern]);

        $found = $this->facts->search($pattern);

        $this->assertEquals('1. ' . $found[0] . PHP_EOL . '2. ' . $found[1] . '3. ' . $found[2], $command->response());
    }

    public function testResponseReturnExpectedOneWhenOneWithSpaceExistPattern()
    {
        $pattern = 'PHP 5';
        $command = new SearchCommand($this->facts, ['argument' => $pattern]);

        $found = $this->facts->search($pattern);

        $this->assertEquals($found[0], $command->response());
    }

    public function testResponseReturnExpectedOneWhenThreeInTextExistPattern()
    {
        $pattern = 'ORM';
        $command = new SearchCommand($this->facts, ['argument' => $pattern]);

        $found = $this->facts->search($pattern);

        $this->assertEquals($found[0], $command->response());
    }

    public function testResponseReturnNothingWhenOneInsideWordExistPattern()
    {
        $pattern = 'octrin';
        $command = new SearchCommand($this->facts, ['argument' => $pattern]);

        $this->assertEquals('Nothing found on ' . $pattern . ' request', $command->response());
    }
}
