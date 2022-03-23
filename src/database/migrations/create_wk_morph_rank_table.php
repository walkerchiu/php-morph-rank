<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWkMorphRankTable extends Migration
{
    public function up()
    {
        Schema::create(config('wk-core.table.morph-rank.levels'), function (Blueprint $table) {
            $table->uuid('id');
            $table->nullableUuidMorphs('host');
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->nullableUuidMorphs('morph');
            $table->unsignedBigInteger('order')->nullable();
            $table->boolean('is_enabled')->default(1);

            $table->timestampsTz();
            $table->softDeletes();

            $table->primary('id');
            $table->index('serial');
            $table->index('identifier');
            $table->index('is_enabled');
            $table->index(['host_type', 'host_id', 'is_enabled']);
        });
        if (!config('wk-morph-rank.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.morph-rank.levels_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }

        Schema::create(config('wk-core.table.morph-rank.statuses'), function (Blueprint $table) {
            $table->uuid('id');
            $table->nullableUuidMorphs('host');
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->nullableUuidMorphs('morph');
            $table->unsignedBigInteger('order')->nullable();
            $table->boolean('is_enabled')->default(1);

            $table->timestampsTz();
            $table->softDeletes();

            $table->primary('id');
            $table->index('serial');
            $table->index('identifier');
            $table->index('is_enabled');
            $table->index(['host_type', 'host_id', 'is_enabled']);
        });
        if (!config('wk-morph-rank.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.morph-rank.statuses_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }
    }

    public function down() {
        Schema::dropIfExists(config('wk-core.table.morph-rank.statuses_lang'));
        Schema::dropIfExists(config('wk-core.table.morph-rank.statuses'));
        Schema::dropIfExists(config('wk-core.table.morph-rank.levels_lang'));
        Schema::dropIfExists(config('wk-core.table.morph-rank.levels'));
    }
}
