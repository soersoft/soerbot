<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Configurator;
use SoerBot\Database\Models\Rank;
use SoerBot\Services\RankService;

class RankServiceTest extends TestCase
{
    /**
     * @var RankService
     */
    private $service;

    /**
     * Setup env and migrations.
     */
    public function setUp()
    {
        parent::setUp();

        $this->service = new RankService();

        $configPath = __DIR__ . '/../Env/config.test.yaml';
        Configurator::setConfigPath($configPath);

        require __DIR__ . '/../../database/migrations.php';
    }

    /**
     * Check create a new User and set passed rank.
     */
    public function testCreateNewUserAndSetRank(): void
    {
        $user = rand(1, 1000);
        $rank = 1000;
        $this->service->update($user, $rank);

        $this->assertEquals($user, Rank::first()->user);
        $this->assertEquals($rank, Rank::first()->rank);
        $this->assertCount(1, Rank::all());
    }

    /**
     * Check update rank for a exists user.
     */
    public function testUpdateUserAndSetRank(): void
    {
        $user = rand(1, 1000);
        $rank = 1000;
        Rank::create(['user' => $user, 'rank' => $rank]);

        $this->service->update($user, $rank);

        $this->assertEquals($user, Rank::first()->user);
        $this->assertEquals($rank * 2, Rank::first()->rank);
        $this->assertCount(1, Rank::all());
    }

    /**
     * Check update decrement rank for a user.
     */
    public function testUpdateUserAndSetNegativeRank(): void
    {
        $user = rand(1, 1000);
        $rank = -1000;
        $this->service->update($user, $rank);

        $this->assertEquals($user, Rank::first()->user);
        $this->assertEquals($rank, Rank::first()->rank);
        $this->assertCount(1, Rank::all());
    }

    /**
     * Get user's rank.
     */
    public function testGetUserRank(): void
    {
        $user = rand(1, 1000);
        $rank = 1000;
        Rank::create(['user' => $user, 'rank' => $rank]);

        $this->assertEquals($rank, $this->service->getRank($user));
    }
}
