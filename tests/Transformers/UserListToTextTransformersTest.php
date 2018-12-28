<?php

namespace Tests\Transformers;

use Tests\TestCase;
use SoerBot\Transformers\UserListsToTextTransformer;

class UserListToTextTransformersTest extends TestCase
{
    /**
     * Check transform data.
     *
     * @return void
     */
    public function testTransformData()
    {
        $transform = new UserListsToTextTransformer();

        $this->assertEquals(
            $this->result(),
            $transform->transform($this->data())
        );
    }

    private function result()
    {
        return file_get_contents(__DIR__ . '/../Fixtures/user_date_trasformed');
    }

    /**
     * Fixture data.
     *
     * @return mixed
     */
    private function data()
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../Fixtures/user_list_data.json'),
            true
        );
    }
}
