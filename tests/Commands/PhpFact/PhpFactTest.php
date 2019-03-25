<?php

namespace Tests\Commands;

use SoerBot\Commands\PhpFact\Implementations\PhpFact;
use Tests\TestCase;

class PhpFactTest extends TestCase
{
    private $fact;

    protected function setUp()
    {
        try {
            $this->fact = new PhpFact();
        } catch (\Throwable $e) {
            $this->fail('Exception with ' . $e->getMessage() . 'was thrown is setUp method!');
        }

        parent::setUp();
    }

    /*------------Exception block------------*/

    /*------------Corner case block------------*/

    /*------------Functional block------------*/
    public function testGetReturnsStringFromStorageArray()
    {
        $allFacts = $this->getPrivateVariableValue($this->fact, 'facts');
        $getFact = $this->fact->get();

        $this->assertTrue(in_array($getFact, $allFacts, true));
    }
}
