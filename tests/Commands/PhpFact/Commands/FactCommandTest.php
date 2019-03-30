<?php

namespace Tests\Commands\PhpFact\Commands;

use Tests\TestCase;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use SoerBot\Commands\PhpFact\Implementations\Commands\FactCommand;

class FactCommandTest extends TestCase
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
        $command = new FactCommand($facts);

        $this->assertIsString($command->response());
    }

    public function testResponseReturnExpectedSting()
    {
        $file = __DIR__ . '/../phpfacts.txt';
        $storage = new FileStorage($file);
        $facts = new PhpFacts($storage);
        $command = new FactCommand($facts);

        $content = $this->getPrivateVariableValue($facts, 'facts');

        $this->assertContains($command->response(), $content);
    }
}
