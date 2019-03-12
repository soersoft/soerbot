<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Commands\Watch\WatcherActors\Karma\Implementations\UserModel;
use SoerBot\Commands\Watch\WatcherActors\Karma\Exceptions\InvalidUserNameException;

class UserModelTest extends TestCase
{
    protected function setUp()
    {
        $this->user = new UserModel();

        parent::setUp();
    }

    public function testIncrementUserKarmaFunction()
    {
        $testUserName = 'username';

        $store = $this->createMock('SoerBot\Commands\Watch\WatcherActors\Karma\Implementations\KarmaStoreJSONFile');
        $store
            ->expects(($this->once()))
            ->method('get')
            ->with('username')
            ->will($this->returnValue(['name' => $testUserName, 'karma' => 0]));

        $this->setPrivateVariableValue($this->user, 'store', $store);

        $this->user->incrementUserKarma($testUserName);
    }

    public function testIncrementUserKarmaException()
    {
        $invalidTestUserName = '';

        $this->expectException(InvalidUserNameException::class);

        $this->user->incrementUserKarma($invalidTestUserName);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
