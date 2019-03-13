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
          ['emoji' => ':star:', 'count' => 5],
          ['emoji' => ':smile:', 'count' => 3],
        ];

        $this->user = new User('Username', $rewards);

        parent::setUp();
    }

    public function testGetReward()
    {
        $this->assertEquals(['emoji' => ':smile:', 'count' => 3], $this->user->getReward(':smile:'));
        $this->assertEquals(['emoji' => ':star:', 'count' => 5], $this->user->getReward(':star:'));
        $this->assertFalse($this->user->getReward(':emoji:'));
    }

    public function testAddReward()
    {
        $this->user->addReward(':thumbsup:', 2);
        $this->assertEquals(['emoji' => ':thumbsup:', 'count' => 2], $this->user->getReward(':thumbsup:'));
    }

    public function testAddToRewardAmount()
    {
        $this->user->changeRewardAmount(':thumbsup:', 5);
        $this->assertEquals(['emoji' => ':thumbsup:', 'count' => 5], $this->user->getReward(':thumbsup:'));

        $this->user->changeRewardAmount(':thumbsup:', -4);
        $this->assertEquals(['emoji' => ':thumbsup:', 'count' => 1], $this->user->getReward(':thumbsup:'));
    }

    public function testIncrementReward()
    {
        $this->user->addReward(':thumbsup:', 2);
        $this->user->incrementReward(':thumbsup:');
        $this->assertEquals(['emoji' => ':thumbsup:', 'count' => 3], $this->user->getReward(':thumbsup:'));
    }

    public function testDecrementReward()
    {
        $this->user->addReward(':thumbsup:', 2);
        $this->user->decrementReward(':thumbsup:');
        $this->assertEquals(['emoji' => ':thumbsup:', 'count' => 1], $this->user->getReward(':thumbsup:'));
    }

    public function testDecrementRewardRemovingRewardIfAmountLessThanNull()
    {
        $this->user->addReward(':thumbsup:', 2);
        $this->user->decrementReward(':thumbsup:');
        $this->user->decrementReward(':thumbsup:');
        $this->assertFalse($this->user->getReward(':thumbsup:'));
    }

    public function testRemoveReward()
    {
        $this->assertEquals(['emoji' => ':star:', 'count' => 5], $this->user->getReward(':star:'));
        $this->assertTrue($this->user->removeReward(':star:'));
        $this->assertFalse($this->user->getReward(':star:'));
    }

    public function testToString()
    {
        $string = '@Username' . PHP_EOL . ':star::star::star::star::star:' . PHP_EOL . ':smile::smile::smile:' . PHP_EOL;
        $this->assertEquals($string, (string)$this->user);
    }

    public function testToStringAsNonMagicMethod()
    {
        $string = '@Username' . PHP_EOL . ':star::star::star::star::star:' . PHP_EOL . ':smile::smile::smile:' . PHP_EOL;
        $this->assertEquals($string, $this->user->toString());
    }

    public function testToStringWithPrefix()
    {
        $string = ':one: @Username' . PHP_EOL . ':star::star::star::star::star:' . PHP_EOL . ':smile::smile::smile:'
          . PHP_EOL;

        $this->user->addPrefix(':one: ');
        $this->assertEquals($string, (string)$this->user);
    }
}
