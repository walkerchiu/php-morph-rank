<?php

namespace WalkerChiu\MorphRank;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\MorphRank\Models\Entities\Status;
use WalkerChiu\MorphRank\Models\Entities\StatusLang;

class StatusTest extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');
    }

    /**
     * To load your package service provider, override the getPackageProviders.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return Array
     */
    protected function getPackageProviders($app)
    {
        return [\WalkerChiu\Core\CoreServiceProvider::class,
                \WalkerChiu\MorphRank\MorphRankServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    /**
     * A basic functional test on Status.
     *
     * For WalkerChiu\MorphRank\Models\Entities\Status
     * 
     * @return void
     */
    public function testStatus()
    {
        // Config
        Config::set('wk-core.onoff.core-lang_core', 0);
        Config::set('wk-morph-rank.onoff.core-lang_core', 0);
        Config::set('wk-core.lang_log', 1);
        Config::set('wk-morph-rank.lang_log', 1);
        Config::set('wk-core.soft_delete', 1);
        Config::set('wk-morph-rank.soft_delete', 1);

        // Give
        $db_morph_1 = factory(Status::class)->create();
        $db_morph_2 = factory(Status::class)->create();
        $db_morph_3 = factory(Status::class)->create(['is_enabled' => 0]);

        // Get records after creation
            // When
            $records = Status::all();
            // Then
            $this->assertCount(3, $records);

        // Delete someone
            // When
            $db_morph_2->delete();
            $records = Status::all();
            // Then
            $this->assertCount(2, $records);

        // Resotre someone
            // When
            Status::withTrashed()
                  ->find($db_morph_2->id)
                  ->restore();
            $record_2 = Status::find($db_morph_2->id);
            $records = Status::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(3, $records);

        // Return Lang class
            // When
            $class = $record_2->lang();
            // Then
            $this->assertEquals($class, StatusLang::class);

        // Scope query on enabled records
            // When
            $records = Status::ofEnabled()
                             ->get();
            // Then
            $this->assertCount(2, $records);

        // Scope query on disabled records
            // When
            $records = Status::ofDisabled()
                             ->get();
            // Then
            $this->assertCount(1, $records);
    }
}
