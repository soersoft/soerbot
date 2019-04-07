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
        $this->expectExceptionMessage('Wrong usage of search command. Argument is less than minimum ' . SearchCommand::PATTERN_MIN_LENGTH . ' chars.');

        new SearchCommand($facts, ['argument' => str_repeat('t', SearchCommand::PATTERN_MIN_LENGTH - 1)]);
    }

    public function testConstructorThrowExceptionWhenPatternIsMoreThanMaxLength()
    {
        $facts = $this->createMock(PhpFacts::class);

        $this->expectException(CommandWrongUsageException::class);
        $this->expectExceptionMessage('Wrong usage of search command. Argument is more than maximum ' . SearchCommand::PATTERN_MAX_LENGTH . ' chars.');

        new SearchCommand($facts, ['argument' => str_repeat('t', SearchCommand::PATTERN_MAX_LENGTH + 1)]);
    }

    /**
     * Corner cases.
     */
    public function testConstructorDontThrowExceptionWhenPatternIsMin()
    {
        $facts = $this->createMock(PhpFacts::class);

        try {
            new SearchCommand($facts, ['argument' => str_repeat('t', SearchCommand::PATTERN_MIN_LENGTH)]);
        } catch (CommandWrongUsageException $e) {
            $this->fail('Exception thrown on min plus one with message ' . $e->getMessage() . '');
        }

        $this->assertTrue(true);
    }

    public function testConstructorDontThrowExceptionWhenPatternIsMax()
    {
        $facts = $this->createMock(PhpFacts::class);

        try {
            new SearchCommand($facts, ['argument' => str_repeat('t', SearchCommand::PATTERN_MAX_LENGTH)]);
        } catch (CommandWrongUsageException $e) {
            $this->fail('Exception thrown on min plus one with message ' . $e->getMessage() . '');
        }

        $this->assertTrue(true);
    }

    public function testResponseReturnNoMoreThanMaximumOutput()
    {
        $maximum = SearchCommand::OUTPUT_MAX_LENGTH;
        $pattern = 'php';
        $command = new SearchCommand($this->facts, ['argument' => $pattern]);

        $this->assertTrue(mb_strlen($command->response()) < $maximum);
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

    public function testSearchFindFiveWhenFiveExistPattern()
    {
        $expected = 'java';
        $command = new SearchCommand($this->facts, ['argument' => $expected]);

        $found = $this->getPrivateVariableValue($command, 'found');

        $this->assertNotEmpty($found);
        $this->assertCount(5, $found);
    }

    public function testResponseReturnNothingWhenNotExistPattern()
    {
        $expected = 'not_exist';
        $command = new SearchCommand($this->facts, ['argument' => $expected]);

        $this->assertEquals('Nothing found with ' . $expected . ' request.', $command->response());
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
        $pattern = 'ооп';
        $command = new SearchCommand($this->facts, ['argument' => $pattern]);

        $found = $this->facts->search($pattern);

        $this->assertEquals('1. ' . $found[0] . PHP_EOL . '2. ' . $found[1], $command->response());
    }

    public function testResponseReturnExpectedThreeWhenThreeExistPattern()
    {
        $pattern = 'код';
        $command = new SearchCommand($this->facts, ['argument' => $pattern]);

        $found = $this->facts->search($pattern);

        $this->assertEquals('1. ' . $found[0] . PHP_EOL . '2. ' . $found[1] . PHP_EOL . '3. ' . $found[2], $command->response());
    }

    public function testResponseReturnExpectedFiveWhenFiveExistPattern()
    {
        $pattern = 'java';
        $command = new SearchCommand($this->facts, ['argument' => $pattern]);

        $found = $this->facts->search($pattern);

        $this->assertEquals('1. ' . $found[0] . PHP_EOL . '2. ' . $found[1] . PHP_EOL . '3. ' . $found[2] . PHP_EOL . '4. ' . $found[3] . PHP_EOL . '5. ' . $found[4], $command->response());
    }

    public function testResponseReturnExpectedOneWhenOneWithSpaceExistPattern()
    {
        $pattern = 'PHP 5';
        $command = new SearchCommand($this->facts, ['argument' => $pattern]);

        $found = $this->facts->search($pattern);

        $this->assertEquals($found[0], $command->response());
    }

    public function testResponseReturnExpectedOneWhenOneAtLineBeginning()
    {
        $pattern = 'помимо';
        $command = new SearchCommand($this->facts, ['argument' => $pattern]);

        $this->assertStringStartsWith('Помимо развитого ООП', $command->response());
    }

    public function testResponseReturnExpectedOneWhenOneAtLineEnd()
    {
        $pattern = 'миксины';
        $command = new SearchCommand($this->facts, ['argument' => $pattern]);

        $this->assertStringEndsWith('они же примеси или миксины.', $command->response());
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

        $this->assertEquals('Nothing found with ' . $pattern . ' request.', $command->response());
    }
}
