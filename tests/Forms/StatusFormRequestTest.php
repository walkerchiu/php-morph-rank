<?php

namespace WalkerChiu\MorphRank;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use WalkerChiu\MorphRank\Models\Entities\Status;
use WalkerChiu\MorphRank\Models\Forms\StatusFormRequest;

class StatusFormRequestTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        //$this->loadLaravelMigrations(['--database' => 'mysql']);
        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');

        $this->request  = new StatusFormRequest();
        $this->rules    = $this->request->rules();
        $this->messages = $this->request->messages();
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
     * Unit test about Authorize.
     *
     * For WalkerChiu\MorphRank\Models\Forms\StatusFormRequest
     * 
     * @return void
     */
    public function testAuthorize()
    {
        $this->assertEquals(true, 1);
    }

    /**
     * Unit test about Rules.
     *
     * For WalkerChiu\MorphRank\Models\Forms\StatusFormRequest
     * 
     * @return void
     */
    public function testRules()
    {
        $faker = \Faker\Factory::create();

        $group_id = 1;
        DB::table(config('wk-core.table.group.groups'))->insert([
            'id'         => $group_id,
            'serial'     => $faker->username,
            'identifier' => $faker->slug,
            'is_enabled' => true,
        ]);

        // Give
        $attributes = [
            'serial'     => $faker->isbn10,
            'identifier' => $faker->slug,
            'morph_type' => config('wk-core.class.group.group'),
            'morph_id'   => $group_id,
            'name'       => $faker->name,
            'is_enabled' => true,
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(false, $fails);

        // Give
        $attributes = [
            'serial'     => $faker->isbn10,
            'identifier' => $faker->slug,
            'morph_type' => '',
            'morph_id'   => $group_id,
            'name'       => $faker->name,
            'is_enabled' => true,
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(true, $fails);
    }
}
