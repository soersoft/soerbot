<?php

namespace Tests\Commands\Leaderboard;

use Tests\TestCase;
use CharlotteDunois\Yasmin\Utils\Collection;
use SoerBot\Commands\Leaderboard\Implementations\User;
use SoerBot\Commands\Leaderboard\Implementations\UsersModel;
use SoerBot\Commands\Leaderboard\Implementations\LeaderBoardStoreJSONFile;

class UsersModelTest extends TestCase
{
    /**
     * @var UsersModel
     */
    protected $users;

    public function setUp()
    {
        $store = new LeaderBoardStoreJSONFile(__DIR__ . '/../../Fixtures/leaderboard.tmp.json');
        $this->users = new UsersModel($store);

        parent::setUp();
    }

    public function testLoad()
    {
        $this->users->load();
        $this->assertInstanceOf(Collection::class, $this->users->all());
    }

    public function testLoadFileNotFoundException()
    {
        $this->expectException(\RuntimeException::class);
        $this->users->save();
    }

    public function testSave()
    {
        $this->users->load();
        $this->assertNotFalse($this->users->save());
    }

    public function testAll()
    {
        $this->users->load();
        $this->assertInstanceOf(Collection::class, $this->users->all());
    }

    public function testAllWhenCollectionIsEmpty()
    {
        $this->assertNull($this->users->all());
    }

    public function testGet()
    {
        $this->users->load();
        $this->assertInstanceOf(User::class, $this->users->get('Username1'));
    }

    public function testGetWhenCollectionIsEmpty()
    {
        $this->assertNull($this->users->get('Username1'));
    }

    public function testCreate()
    {
        $this->users->load();

        $rewards = [
          [
            'emoji' => ':star:',
            'count' => 1,
          ],
        ];

        $user = new User('Username3', $rewards);

        $this->users->create($user);
        $this->assertInstanceOf(User::class, $this->users->get('Username3'));
    }

    public function testUpdate()
    {
        $this->users->load();

        $rewards = [
          [
            'emoji' => ':star:',
            'count' => 2,
          ],
        ];

        $user1 = $this->users->get('Username3');
        $user2 = new User('Username3', $rewards);

        $this->users->update($user1, $user2);

        $this->assertEquals($user2, $this->users->get('Username3'));
    }

    public function testDelete()
    {
        $this->users->load();

        $user = $this->users->get('Username3');
        $this->users->delete($user);

        $this->assertNull($this->users->get('Username3'));
    }
}
