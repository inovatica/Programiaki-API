<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gamifications', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->char('child_id', 36);
            $table->char('game_id', 36);
            $table->char('game_level_id', 36);
            $table->text('data');
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->primary('uuid');
            
            $table->foreign('child_id')
                ->references('uuid')
                ->on('users');
            $table->foreign('game_id')
                ->references('id')
                ->on('games');
            $table->foreign('game_level_id')
                ->references('id')
                ->on('games_levels');
            
            $table->unique(['child_id', 'game_id', 'game_level_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gamifications');
    }
}
