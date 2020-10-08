<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GamesLvlsObjsTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('key')->index();
            $table->boolean('active')->default(1);
            $table->unsignedInteger('pos');
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
        });

        Schema::create('games_levels', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('game_id');
            $table->string('name');
            $table->string('key')->index();
            $table->boolean('active')->default(1);
            $table->unsignedInteger('pos');
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->foreign('game_id')
                ->references('id')
                ->on('games');
        });

        Schema::create('objects_types', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('key')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
        });

        Schema::create('objects_groups', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('key')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
        });

        Schema::create('objects', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('key')->index();
            $table->string('type_id');
            $table->string('group_id')->nullable();
            $table->string('image_id')->nullable();
            $table->string('audio_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');

            $table->foreign('type_id')
                ->references('id')
                ->on('objects_types');

            $table->foreign('group_id')
                ->references('id')
                ->on('objects_groups');

            $table->foreign('image_id')
                ->references('id')
                ->on('files');

            $table->foreign('audio_id')
                ->references('id')
                ->on('files');
        });

        Schema::create('objects_food_chain', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('consumer_id');
            $table->string('meal_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('consumer_id')
                ->references('id')
                ->on('objects');

            $table->foreign('meal_id')
                ->references('id')
                ->on('objects');

            $table->primary('id');
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('key')->index();
            $table->boolean('active')->default(1);
            $table->char('table_id', 36)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
        });

        Schema::create('tags_objects', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('tag_id');
            $table->string('object_id');
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');

            $table->foreign('tag_id')
                ->references('id')
                ->on('tags');

            $table->foreign('object_id')
                ->references('id')
                ->on('objects');
        });

        Schema::create('games_levels_tags_objects', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('games_levels_id');
            $table->string('tags_objects_id');
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');

            $table->foreign('games_levels_id')
                ->references('id')
                ->on('games_levels');

            $table->foreign('tags_objects_id')
                ->references('id')
                ->on('tags_objects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('games_levels_tags_objects');
        Schema::drop('tags_objects');
        Schema::drop('tags');
        Schema::drop('objects_food_chain');
        Schema::drop('objects');
        Schema::drop('objects_types');
        Schema::drop('objects_groups');
        Schema::drop('games_levels');
        Schema::drop('games');
    }
}
