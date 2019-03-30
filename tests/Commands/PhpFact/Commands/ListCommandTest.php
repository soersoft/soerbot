<?php

namespace Tests\Commands\PhpFact\Commands;

use Tests\TestCase;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Implementations\Commands\ListCommand;

class ListCommandTest extends TestCase
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
        $command = new ListCommand($facts);

        $this->assertIsString($command->response());
        $this->assertStringStartsWith('Input ', $command->response());
    }
}
