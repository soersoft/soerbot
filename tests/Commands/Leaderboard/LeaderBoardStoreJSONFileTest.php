<?php

namespace Tests\Commands\Leaderboard;

use Tests\TestCase;
use SoerBot\Commands\Leaderboard\Store\LeaderBoardStoreJSONFile;
use SoerBot\Commands\Leaderboard\Interfaces\LeaderBoardStoreInterface;
use SoerBot\Commands\Leaderboard\Exceptions\StoreFileNotFoundException;
use SoerBot\Commands\Leaderboard\Exceptions\TooFewArgumentsForUserAdding;

class LeaderBoardStoreJSONFileTest extends TestCase
{
    protected $store;

    public function testThatWeGetExceptionWhenCouldNotFindFile()
    {
        $this->expectException(StoreFileNotFoundException::class);
        (new LeaderBoardStoreJSONFile('filename.json'))->load();
    }

    public function testThatWeCanLoadStoreFile()
    {
        try {
            $store = new LeaderBoardStoreJSONFile(__DIR__ . '/../../Fixtures/leaderboard.tmp.json');
            $this->assertTrue($store->load());

            return $store;
        } catch (\Exception $e) {
            $this->fail($e->getMessage());

            return null;
        }
    }

    /**
     * @depends testThatWeCanLoadStoreFile
     * @param LeaderBoardStoreInterface $store
     */
    public function testThatWeCanGetAllTheData(LeaderBoardStoreInterface $store)
    {
        $users = $store->toArray();

        $this->assertIsArray($users);
        $this->assertNotEmpty($users);
    }

    /**
     * @depends testThatWeCanLoadStoreFile
     * @param LeaderBoardStoreInterface $store
     */
    public function testThatWeCanLoadStoreFileInRightWay(LeaderBoardStoreInterface $store)
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

        $this->assertEquals($array, $store->toArray());
    }

    /**
     * @depends testThatWeCanLoadStoreFile
     * @param LeaderBoardStoreInterface $store
     */
    public function testThatWeCanSaveStoreFile(LeaderBoardStoreInterface $store)
    {
        $this->assertNotFalse($store->save());
    }

    /**
     * @depends testThatWeCanLoadStoreFile
     * @param LeaderBoardStoreInterface $store
     */
    public function testThatWeCanGetUser(LeaderBoardStoreInterface $store)
    {
        $user = $store->get('Username1');
        $this->assertIsArray($user);

        $this->assertArrayHasKey('username', $user);
        $this->assertArrayHasKey('rewards', $user);

        $this->assertSame('Username1', $user['username']);

        $this->assertIsArray($user['rewards']);
    }

    /**
     * @depends testThatWeCanLoadStoreFile
     * @param LeaderBoardStoreInterface $store
     */
    public function testThatWeCanNotGetNonExistingUser(LeaderBoardStoreInterface $store)
    {
        $this->assertNull($store->get('Username10'));
    }

    /**
     * @depends testThatWeCanLoadStoreFile
     * @param LeaderBoardStoreInterface $store
     */
    public function testThatWeGetExceptionWhenPassedTooFewArgs(LeaderBoardStoreInterface $store)
    {
        $this->expectException(TooFewArgumentsForUserAdding::class);
        $store->add(['Username']);
    }

    /**
     * @depends testThatWeCanLoadStoreFile
     * @param LeaderBoardStoreInterface $store
     */
    public function testThatWeCanAddUser(LeaderBoardStoreInterface $store)
    {
        $rewards = [
          ['emoji' => ':star:'],
          ['count' => 3],
        ];

        $store->add(['Username3', $rewards]);

        $store->save();
        $store->load();

        $this->assertSame('Username3', $store->get('Username3')['username']);
    }

    /**
     * @depends testThatWeCanLoadStoreFile
     * @depends testThatWeCanAddUser
     * @param LeaderBoardStoreInterface $store
     */
    public function testThatWeCanRemoveUser(LeaderBoardStoreInterface $store)
    {
        $store->remove('Username3');

        $store->save();
        $store->load();

        $this->assertNull($store->get('Username3'));
    }

    /**
     * @depends testThatWeCanLoadStoreFile
     * @param LeaderBoardStoreInterface $store
     */
    public function testThatWeCanNotRemoveNonExistingUser(LeaderBoardStoreInterface $store)
    {
        $this->assertNull($store->remove('Username10'));
    }

    /**
     * @depends testThatWeCanLoadStoreFile
     * @param LeaderBoardStoreInterface $store
     */
    public function testThatWeCanCheckExistingOfUser(LeaderBoardStoreInterface $store)
    {
        $userExists = $this->getPrivateMethod(LeaderBoardStoreJSONFile::class, 'userExists');

        $this->assertTrue($userExists->invokeArgs($store, ['Username1']));
        $this->assertFalse($userExists->invokeArgs($store, ['Username10']));
    }
}
