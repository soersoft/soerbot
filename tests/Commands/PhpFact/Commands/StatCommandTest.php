<?php

namespace Tests\Commands\PhpFact\Commands;

use Tests\TestCase;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use SoerBot\Commands\PhpFact\Implementations\Commands\StatCommand;

class StatCommandTest extends TestCase
{
    /**
     * Exceptions.
     */

    /**
     * Corner cases.
     */

    /**
     * Functionality.
     */
    public function testResponseReturnExpected()
    {
        $facts = $this->createMock(PhpFacts::class);
        $command = new StatCommand($facts);

        $this->assertIsString($command->response());
    }

    public function testResponseReturnExpectedSingleSting()
    {
        $expected = 'We have 1 fact in collection.';

        $file = __DIR__ . '/../phpfacts.txt';
        $storage = new FileStorage($file);
        $facts = new PhpFacts($storage);
        $this->setPrivateVariableValue($facts, 'facts', ['test']);
        $command = new StatCommand($facts);

        $this->assertEquals($expected, $command->response());
    }

    public function testResponseReturnExpectedPluralSting()
    {
        $expected = 'We have 5 facts in collection.';

        $file = __DIR__ . '/../phpfacts.txt';
        $storage = new FileStorage($file);
        $facts = new PhpFacts($storage);
        $command = new StatCommand($facts);

        $this->assertEquals($expected, $command->response());
    }
}
