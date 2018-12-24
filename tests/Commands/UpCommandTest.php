<?php

namespace Tests\Commands;

use Tests\TestCase;

class UpCommandTest extends TestCase
{
   public function testCanBeBuildWithoutWarning(): void
    {
        $this->assertEquals(
            'one positive test for travis build',
            'one positive test for travis build'
        );
    }
}