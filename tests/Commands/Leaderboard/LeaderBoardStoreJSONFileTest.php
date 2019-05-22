<?php

namespace Tests\Commands\Leaderboard;

use Tests\TestCase;
use SoerBot\Commands\Leaderboard\Exceptions\StoreFileNotFoundException;
use SoerBot\Commands\Leaderboard\Implementations\LeaderBoardStoreJSONFile;
use SoerBot\Commands\Leaderboard\Interfaces\LeaderBoardStoreInterface;

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
        $this->assertIsArray($this->store->load());
    }

    public function testLoadFileNotFoundException()
    {
        $this->expectException(StoreFileNotFoundException::class);
        (new LeaderBoardStoreJSONFile('filename.json'))->load();
    }

    public function testLoadEmptyStore()
    {
        $this->store = new LeaderBoardStoreJSONFile(__DIR__ . '/../../Fixtures/leaderboard.empty.tmp.json');
        $this->assertNull($this->store->load());
    }

    public function testSave()
    {
        $data = [];
        $this->assertNotFalse($this->store->save($data));

        $data = [
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
                'emoji' => ':star:',
                'count' => 5,
              ],
            ],
          ],
        ];
        $this->assertIsInt($this->store->save($data));
    }
}
