<?php

namespace Tests\Commands\Leaderboard;

use Tests\TestCase;
use SoerBot\Commands\Leaderboard\Store\LeaderBoardStoreJSONFile;
use SoerBot\Commands\Leaderboard\Interfaces\LeaderBoardStoreInterface;
use SoerBot\Commands\Leaderboard\Exceptions\StoreFileNotFoundException;
use SoerBot\Commands\Leaderboard\Exceptions\TooFewArgumentsForUserAdding;

class LeaderBoardStoreJSONFileTest extends TestCase
{
    /**
     * @var LeaderBoardStoreInterface
     */
    protected $store;

    public function setUp(): void
    {
        $this->store = new LeaderBoardStoreJSONFile(__DIR__ . '/../../Fixtures/leaderboard.tmp.json');
        $this->store->load();
    }

    public function testLoad()
    {
        $this->assertTrue($this->store->load());
    }

    public function testLoadFileNotFoundException()
    {
        $this->expectException(StoreFileNotFoundException::class);
        (new LeaderBoardStoreJSONFile('filename.json'))->load();
    }

    public function testLoadEmptyStore()
    {
        $this->store = new LeaderBoardStoreJSONFile(__DIR__ . '/../../Fixtures/leaderboard.empty.tmp.json');
        $this->assertTrue($this->store->load());
    }

    public function testToArray()
    {
        $users = $this->store->toArray();
        $this->assertIsArray($users);
    }

    public function testToArrayReturnedTheSameData()
    {
        $array = [
          [
            'username' => 'Username1',
            'rewards' => [
              [
                'emoji' => ':star:',
                'count' => 8,
              ],
              [
                'emoji' => ':medal:',
                'count' => 4,
              ],
            ],
          ],
          [
            'username' => 'Username2',
            'rewards' => [
              [
                'emoji' => ':smile:',
                'count' => 5,
              ],
            ],
          ],
        ];

        $this->assertSame($array, $this->store->toArray());
    }

    public function testSave()
    {
        $this->assertNotFalse($this->store->save());
    }

    public function testGet()
    {
        $user = $this->store->get('Username1');

        $this->assertArrayHasKey('username', $user);
        $this->assertArrayHasKey('rewards', $user);

        $this->assertSame('Username1', $user['username']);

        $this->assertIsArray($user['rewards']);
    }

    public function testGetNonExistingUser()
    {
        $this->assertNull($this->store->get('Username10'));
    }

    public function testGetTooFewArgumentsForUserAddingException()
    {
        $this->expectException(TooFewArgumentsForUserAdding::class);
        $this->store->add(['Username']);
    }

    public function testAdd()
    {
        $rewards = [
          ['emoji' => ':star:'],
          ['count' => 3],
        ];

        $this->store->add(['Username3', $rewards]);
        $this->assertSame('Username3', $this->store->get('Username3')['username']);
    }

    public function testRemove()
    {
        $rewards = [
          ['emoji' => ':star:'],
          ['count' => 3],
        ];

        $this->store->add(['Username3', $rewards]);
        $this->store->remove('Username3');

        $this->assertNull($this->store->get('Username3'));
    }

    public function testRemoveNonExistingUser()
    {
        $this->assertNull($this->store->remove('Username10'));
    }

    public function testExists()
    {
        $userExists = $this->getPrivateMethod(LeaderBoardStoreJSONFile::class, 'userExists');

        $this->assertTrue($userExists->invokeArgs($this->store, ['Username1']));
        $this->assertFalse($userExists->invokeArgs($this->store, ['Username10']));
    }
}
