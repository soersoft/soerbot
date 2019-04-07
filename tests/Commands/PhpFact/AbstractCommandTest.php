<?php

namespace Tests\Commands\PhpFact\Commands;

use Tests\TestCase;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Abstractions\CommandInterface;
use SoerBot\Commands\PhpFact\Abstractions\AbstractCommandWithArguments;
use SoerBot\Commands\PhpFact\Abstractions\AbstractCommandWithoutArguments;

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
    public function testConstructorMakeObjectWithoutArguments()
    {
        $facts = $this->createMock(PhpFacts::class);
        $command = new class($facts) extends AbstractCommandWithoutArguments {
            public function response(): string
            {
                return '';
            }
        };

        $this->assertInstanceOf(CommandInterface::class, $command);
    }

    public function testConstructorMakeObjectWithArguments()
    {
        $facts = $this->createMock(PhpFacts::class);
        $command = new class($facts, []) extends AbstractCommandWithArguments {
            public function response(): string
            {
                return '';
            }
        };

        $this->assertInstanceOf(CommandInterface::class, $command);
    }
}
