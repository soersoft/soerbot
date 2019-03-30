<?php

namespace Tests\Commands\PhpFact\Commands;

use Tests\TestCase;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use SoerBot\Commands\PhpFact\Exceptions\CommandWrongUsageException;
use SoerBot\Commands\PhpFact\Implementations\Commands\FactExtendedCommand;

class FactExtendedCommandTest extends TestCase
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
    public function testConstructorThrowExceptionWhenPositionArgumentIsEmpty()
    {
        $facts = $this->createMock(PhpFacts::class);

        $this->expectException(CommandWrongUsageException::class);

        new FactExtendedCommand($facts, ['position' => '']);
    }

    public function testConstructorThrowExceptionWhenPositionArgumentNotNumeric()
    {
        $facts = $this->createMock(PhpFacts::class);

        $this->expectException(CommandWrongUsageException::class);

        new FactExtendedCommand($facts, ['position' => 'fail']);
    }

    /**
     * Corner cases.
     */

    /**
     * Functionality.
     */
    public function testResponseReturnExpected()
    {
        $command = new FactExtendedCommand($this->facts, ['position' => 1]);

        $this->assertIsString($command->response());
    }

    public function testResponseReturnExpectedStringWhenFactExist()
    {
        $command = new FactExtendedCommand($this->facts, ['position' => 2]);

        $content = $this->getPrivateVariableValue($this->facts, 'facts');

        $this->assertEquals($command->response(), $content[1]);
    }

    public function testResponseReturnExpectedStringWhenFactNotExist()
    {
        $position = 100;
        $command = new FactExtendedCommand($this->facts, ['position' => $position]);
        $expected = 'The ' . $position . ' is wrong fact. Use $phpfact stat to find right position number.';

        $this->assertEquals($expected, $command->response());
    }
}
