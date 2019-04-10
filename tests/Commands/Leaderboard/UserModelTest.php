<?php

namespace Tests\Commands\Leaderboard;

use Tests\TestCase;
use SoerBot\Commands\Leaderboard\Implementations\User;
use SoerBot\Commands\Leaderboard\Implementations\UserModel;
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
            'count' => 1,
          ],
        ];

        $store = $this->getMockBuilder('LeaderboardStore')->setMethods(['add', 'save'])->getMock();

        $store->expects($this->once())->method('add')->with(['Username', $rewards])->willReturn(true);
        $store->expects($this->once())->method('save')->willReturn(1);

        $this->setPrivateVariableValue($this->users, 'store', $store);

        $this->users->incrementReward('Username', '🏅');
    }

    public function testGetLeaderboardAsString()
    {
        $usersData = [
          new User('Username1', [['emoji' => '⭐', 'count' => '1']]),
          new User('Username2', [['emoji' => '⭐', 'count' => '2']]),
          new User('Username3', [['emoji' => '⭐', 'count' => '1'], ['emoji' => '🏅', 'count' => '1']]),
        ];

        $this->setPrivateVariableValue($this->users, 'users', $usersData);

        $string = ':one: Username1' . PHP_EOL . '⭐' . PHP_EOL . PHP_EOL .
                  ':two: Username2' . PHP_EOL . '⭐⭐' . PHP_EOL . PHP_EOL.
                  ':three: Username3' . PHP_EOL . '⭐' . PHP_EOL . '🏅' . PHP_EOL . PHP_EOL;

        $this->assertSame($string, $this->users->getLeaderBoardAsString());
    }

    public function testSort()
    {
        $usersData = [
          new User('Username1', [['emoji' => '⭐', 'count' => '1']]),
          new User('Username2', [['emoji' => '⭐', 'count' => '2']]),
          new User('Username3', [['emoji' => '⭐', 'count' => '1'], ['emoji' => '🏅', 'count' => '1']]),
        ];

        $this->setPrivateVariableValue($this->users, 'users', $usersData);

        $stringDesc = ':one: Username3' . PHP_EOL . '⭐' . PHP_EOL . '🏅' . PHP_EOL . PHP_EOL .
                      ':two: Username2' . PHP_EOL . '⭐⭐' . PHP_EOL . PHP_EOL .
                      ':three: Username1' . PHP_EOL . '⭐' . PHP_EOL . PHP_EOL;

        $stringAsc = ':one: Username1' . PHP_EOL . '⭐' . PHP_EOL . PHP_EOL .
                     ':two: Username2' . PHP_EOL . '⭐⭐' . PHP_EOL . PHP_EOL .
                     ':three: Username3' . PHP_EOL . '⭐' . PHP_EOL . '🏅' . PHP_EOL . PHP_EOL;

        $this->assertSame($stringDesc, $this->users->sort()->getLeaderBoardAsString());
        $this->assertSame($stringAsc, $this->users->sort('asc')->getLeaderBoardAsString());
    }

    public function testRemoveRewardsByType()
    {
        $usersData = [
            new User('Username1', [['emoji' => '⭐', 'count' => '1']]),
            new User('Username3', [['emoji' => '⭐', 'count' => '1'], ['emoji' => '🏅', 'count' => '1']]),
        ];

        $this->setPrivateVariableValue($this->users, 'users', $usersData);

        $this->assertTrue($this->users->removeRewardsByType('Username1', '⭐'));
        $this->assertTrue($this->users->removeRewardsByType('Username3', '🏅'));
    }
}
