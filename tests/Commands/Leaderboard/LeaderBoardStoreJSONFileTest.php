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

    public function tearDown(): void
    {
        copy(__DIR__ . '/../../Fixtures/leaderboard.json', __DIR__ . '/../../Fixtures/leaderboard.tmp.json');
    }

    public function testConstructThrowExceptionWhenFileNotFound()
    {
        $file = __DIR__ . '/not_exist.json';
        $this->expectException(StoreFileNotFoundException::class);
        $this->expectExceptionMessage('File ' . $file . ' was not found.');

        new LeaderBoardStoreJSONFile($file);
    }

    public function testLoadThrowExceptionWhenFileNotFound()
    {
        $file = __DIR__ . '/not_exist.json';
        $this->expectException(StoreFileNotFoundException::class);
        $this->expectExceptionMessage('File ' . $file . ' was not found.');

        $this->setPrivateVariableValue($this->store, 'file', $file);
        $this->store->load();
    }

    public function testGetThrowTooFewArgumentsForUserAddingException()
    {
        $this->expectException(TooFewArgumentsForUserAdding::class);
        $this->store->add(['Username']);
    }

    public function testLoadReturnTrueWhenEmptyFile()
    {
        $store = new LeaderBoardStoreJSONFile(__DIR__ . '/../../Fixtures/leaderboard.empty.tmp.json');
        $this->assertTrue($store->load());
    }

    public function testLoadReturnTrueWhenFileWithJson()
    {
        $store = new LeaderBoardStoreJSONFile(__DIR__ . '/../../Fixtures/leaderboard.tmp.json');
        $this->assertTrue($store->load());
    }

    public function testSaveReturnFalseWhenFileCannotBeWrite()
    {
        $file = __DIR__ . '/..';
        $this->setPrivateVariableValue($this->store, 'file', $file);

        $this->assertFalse($this->store->save());
    }

    public function testSaveReturnExpectedWhenEmptyData()
    {
        $this->setPrivateVariableValue($this->store, 'data', []);

        $this->assertEquals(2, $this->store->save());
    }

    // @see надо ли этот тест или это уже перебор?
    public function testSaveReturnExpected()
    {
        $data = $this->getPrivateVariableValue($this->store, 'data');
        $count = mb_strlen(json_encode(array_values($data), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        $this->assertEquals($count, $this->store->save());
    }

    public function testGetReturnExpectedData()
    {
        $user = $this->store->get('Username1');

        $this->assertArrayHasKey('username', $user);
        $this->assertArrayHasKey('rewards', $user);

        $this->assertIsString($user['username']);
        $this->assertIsArray($user['rewards']);

        $this->assertSame('Username1', $user['username']);
    }

    public function testGetReturnNullWhenUserNotExist()
    {
        $this->assertNull($this->store->get('NotExist'));
    }

    public function testAddReturnExpectedWhenAddUser()
    {
        $rewards = [
            ['emoji' => ':star:'],
            ['count' => 3],
        ];

        $this->store->add(['Username3', $rewards]);
        $this->assertSame('Username3', $this->store->get('Username3')['username']);
    }

    public function testRemoveWorkAsExpected()
    {
        $rewards = [
            ['emoji' => ':star:'],
            ['count' => 3],
        ];

        $this->store->add(['Username3', $rewards]);
        $this->store->remove('Username3');

        $this->assertNull($this->store->get('Username3'));
    }

    public function testToArrayReturnExpectedType()
    {
        $users = $this->store->toArray();
        $this->assertIsArray($users);
    }

    public function testToArrayReturnExpectedArray()
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

    public function testUserExistsWorkAsExpected()
    {
        $userExists = $this->getPrivateMethod(LeaderBoardStoreJSONFile::class, 'userExists');

        $this->assertTrue($userExists->invokeArgs($this->store, ['Username1']));
        $this->assertFalse($userExists->invokeArgs($this->store, ['Username10']));
    }
}
