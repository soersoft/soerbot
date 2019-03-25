<?php

namespace Tests\Commands\Leaderboard;

use Tests\TestCase;
use SoerBot\Commands\Leaderboard\Implementations\User;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    public function setUp(): void
    {
        $rewards = [
          ['emoji' => '⭐', 'count' => 5],
          ['emoji' => '🏅', 'count' => 3],
        ];

        $this->user = new User('Username', $rewards);

        parent::setUp();
    }

    public function testGetReward()
    {
        $this->assertEquals(['emoji' => '🏅', 'count' => 3], $this->user->getReward('🏅'));
        $this->assertEquals(['emoji' => '⭐', 'count' => 5], $this->user->getReward('⭐'));
        $this->assertFalse($this->user->getReward(':emoji:'));
    }

    public function testAddReward()
    {
        $this->user->addReward('⭐', 2);
        $this->assertEquals(['emoji' => '⭐', 'count' => 2], $this->user->getReward('⭐'));
    }

    public function testAddToRewardAmount()
    {
        $this->user->changeRewardAmount('⭐', 5);
        $this->assertEquals(['emoji' => '⭐', 'count' => 10], $this->user->getReward('⭐'));

        $this->user->changeRewardAmount('⭐', -4);
        $this->assertEquals(['emoji' => '⭐', 'count' => 6], $this->user->getReward('⭐'));
    }

    public function testIncrementReward()
    {
        $this->user->addReward('⭐', 2);
        $this->user->incrementReward('⭐');
        $this->assertEquals(['emoji' => '⭐', 'count' => 3], $this->user->getReward('⭐'));
    }

    public function testDecrementReward()
    {
        $this->user->addReward('⭐', 2);
        $this->user->decrementReward('⭐');
        $this->assertEquals(['emoji' => '⭐', 'count' => 1], $this->user->getReward('⭐'));
    }

    public function testDecrementRewardRemovingRewardIfAmountLessThanNull()
    {
        $this->user->addReward('⭐', 2);
        $this->user->decrementReward('⭐');
        $this->user->decrementReward('⭐');
        $this->assertFalse($this->user->getReward('⭐'));
    }

    public function testRemoveReward()
    {
        $this->assertEquals(['emoji' => '⭐', 'count' => 5], $this->user->getReward('⭐'));
        $this->assertTrue($this->user->removeReward('⭐'));
        $this->assertFalse($this->user->getReward('⭐'));
    }

    public function testToString()
    {
        $string = 'Username' . PHP_EOL . '⭐⭐⭐⭐⭐' . PHP_EOL . '🏅🏅🏅' .
          PHP_EOL;
        $this->assertEquals($string, (string)$this->user);
    }

    public function testToStringAsNonMagicMethod()
    {
        $string = 'Username' . PHP_EOL . '⭐⭐⭐⭐⭐' . PHP_EOL . '🏅🏅🏅' .
          PHP_EOL;
        $this->assertEquals($string, $this->user->toString());
    }

    public function testToStringWithPrefix()
    {
        $string = ':one: Username' . PHP_EOL . '⭐⭐⭐⭐⭐' . PHP_EOL . '🏅🏅🏅'
          . PHP_EOL;

        $this->user->addPrefix(':one: ');
        $this->assertEquals($string, (string)$this->user);
    }

    public function testGetPointsAmount()
    {
        $this->assertEquals(20, $this->user->getPointsAmount());
    }
}
