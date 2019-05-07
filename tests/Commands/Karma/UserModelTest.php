<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Commands\Karma\Implementations\UserModel;
use SoerBot\Commands\Karma\Exceptions\InvalidUserNameException;

class UserModelTest extends TestCase
{
    protected function setUp()
    {
        $this->user = new UserModel();

        parent::setUp();
    }

    public function testIncrementKarmaFunction()
    {
        $testUserName = 'username';

        $store = $this->createMock('SoerBot\Commands\Karma\Implementations\KarmaStoreJSONFile');
        $store
            ->expects(($this->once()))
            ->method('get')
            ->with('username')
            ->will($this->returnValue(['name' => $testUserName, 'karma' => 0]));

        $this->setPrivateVariableValue($this->user, 'store', $store);

        $this->user->incrementKarma($testUserName);
    }

    public function testIncrementKarmaException()
    {
        $invalidTestUserName = '';

        $this->expectException(InvalidUserNameException::class);

        $this->user->incrementKarma($invalidTestUserName);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
