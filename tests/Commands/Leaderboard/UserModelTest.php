<?php

namespace Tests\Commands\Leaderboard;

use Tests\TestCase;
use SoerBot\Commands\Leaderboard\Implementations\UserModel;
use SoerBot\Commands\Leaderboard\Implementations\User;
use SoerBot\Commands\Leaderboard\Store\LeaderBoardStoreJSONFile;

class UserModelTest extends TestCase
{
    /**
     * @var \SoerBot\Commands\Leaderboard\Implementations\UserModel
     */
    protected $users;

    public function setUp()
    {
        $store = new LeaderBoardStoreJSONFile(__DIR__ . '/../../Fixtures/leaderboard.tmp.json');
        $this->users = UserModel::getInstance($store);

        parent::setUp();
    }

    public function testIncrementReward()
    {
        $rewards = [
          [
            'emoji' => '🏅',
            'count' => 1
          ]
        ];

        $store = $this->getMockBuilder('LeaderboardStore')->setMethods(['add', 'save'])->getMock();

        $store->expects($this->once())->method('add')->with(['Username', $rewards])->willReturn(true);
        $store->expects($this->once())->method('save')->willReturn(1);

        $this->setPrivateVariableValue($this->users, 'store', $store);

        $this->assertTrue($this->users->incrementReward('Username', '🏅'));
    }

    public function testGetLeaderboardAsString()
    {
        $usersData = [
          new User('Username1', [['emoji' => ':star:', 'count' => '1']]),
          new User('Username2', [['emoji' => ':star:', 'count' => '2']]),
          new User('Username3', [['emoji' => ':star:', 'count' => '1'], ['emoji' => ':medal:', 'count' => '1']]),
        ];

        $string = <<<EOT
:one: Username1
:star:

:two: Username2
:star::star:

:three: Username3
:star:
:medal:


EOT;

        $this->setPrivateVariableValue($this->users, 'users', $usersData);

        $this->assertSame($string, $this->users->getLeaderBoardAsString());

    }

}
