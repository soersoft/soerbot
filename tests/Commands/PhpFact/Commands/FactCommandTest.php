<?php

namespace Tests\Commands\PhpFact\Commands;

use Tests\TestCase;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use SoerBot\Commands\PhpFact\Implementations\Commands\FactCommand;
use SoerBot\Commands\PhpFact\Exceptions\CommandWrongUsageException;

class FactCommandTest extends TestCase
{
    private $facts;

    protected function setUp()
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
    public function testConstructorThrowExceptionWhenPositionArgumentNotNumeric()
    {
        $facts = $this->createMock(PhpFacts::class);

        $this->expectException(CommandWrongUsageException::class);

        new FactCommand($facts, ['argument' => 'fail']);
    }

    /**
     * Corner cases.
     */
    public function testResponseWithArgumentsWhenPositionIsZero()
    {
        $position = 0;
        $command = new FactCommand($this->facts, ['argument' => $position]);
        $expected = 'The ' . $position . ' is wrong fact. Use $phpfact stat to find right position number.';

        $this->assertEquals($expected, $command->response());
    }

    public function testResponseWithArgumentsWhenPositionIsOne()
    {
        $position = 1;
        $command = new FactCommand($this->facts, ['argument' => $position]);

        $content = $this->getPrivateVariableValue($this->facts, 'facts');

        $this->assertEquals($content[0], $command->response());
    }

    public function testResponseWithArgumentsWhenPositionIsFive()
    {
        $position = 5;
        $command = new FactCommand($this->facts, ['argument' => $position]);

        $content = $this->getPrivateVariableValue($this->facts, 'facts');

        $this->assertEquals($content[4], $command->response());
    }

    public function testResponseWithArgumentsWhenPositionMoreThanCount()
    {
        $position = count(file(__DIR__ . '/../phpfacts.txt')) + 1;
        $command = new FactCommand($this->facts, ['argument' => $position]);
        $expected = 'The ' . $position . ' is wrong fact. Use $phpfact stat to find right position number.';

        $this->assertEquals($expected, $command->response());
    }

    /**
     * Functionality.
     */
    public function testResponseWithoutArgumentsReturnExpected()
    {
        $command = new FactCommand($this->facts, []);

        $this->assertIsString($command->response());
    }

    public function testResponseWithoutArgumentsReturnExpectedSting()
    {
        $command = new FactCommand($this->facts, []);

        $content = $this->getPrivateVariableValue($this->facts, 'facts');

        $this->assertContains($command->response(), $content);
    }

    public function testResponseWithArgumentsReturnExpected()
    {
        $command = new FactCommand($this->facts, ['argument' => 1]);

        $this->assertIsString($command->response());
    }

    public function testResponseWithArgumentsReturnExpectedStringWhenFactExist()
    {
        $command = new FactCommand($this->facts, ['argument' => 2]);

        $content = $this->getPrivateVariableValue($this->facts, 'facts');

        $this->assertEquals($content[1], $command->response());
    }

    public function testResponseWithArgumentsReturnExpectedStringWhenFactNotExist()
    {
        $position = 100;
        $command = new FactCommand($this->facts, ['argument' => $position]);
        $expected = 'The ' . $position . ' is wrong fact. Use $phpfact stat to find right position number.';

        $this->assertEquals($expected, $command->response());
    }

    public function testValidPositionIsEmptyWhenPositionArgumentNotExist()
    {
        $command = new FactCommand($this->facts, []);

        $this->assertEmpty($this->getPrivateVariableValue($command, 'position'));
    }

    public function testValidPositionIsSetWhenPositionArgumentExist()
    {
        $command = new FactCommand($this->facts, ['argument' => 22]);

        $this->assertEquals(22, $this->getPrivateVariableValue($command, 'position'));
    }
}
