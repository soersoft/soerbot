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

    public function testResponseReturnExpectedSting()
    {
        $file = __DIR__ . '/../phpfacts.txt';
        $storage = new FileStorage($file);
        $facts = new PhpFacts($storage);
        $command = new StatCommand($facts);

        $count = count($this->getPrivateVariableValue($facts, 'facts'));
        $expected = 'We have ' . ($count > 1 ? $count . ' facts' : $count . ' fact') . ' in collection.';

        $this->assertEquals($expected, $command->response());
    }
}
