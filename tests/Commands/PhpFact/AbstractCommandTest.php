<?php

namespace Tests\Commands\PhpFact\Commands;

use Tests\TestCase;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Abstractions\AbstractCommand;

class AbstractCommandTest extends TestCase
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
    public function testConstructorMakeRightObject()
    {
        $facts = $this->createMock(PhpFacts::class);
        $command = new class($facts) extends AbstractCommand {
            public function response(): string
            {
                return '';
            }
        };

        $this->assertInstanceOf(AbstractCommand::class, $command);
    }
}
