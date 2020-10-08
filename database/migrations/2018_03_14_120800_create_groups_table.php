<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->char('institution_id', 36);
            $table->char('babysitter_id', 36);
            $table->timestamps();
            $table->softDeletes();
            
            $table->primary('id');
            
            $table->foreign('institution_id')
                ->references('id')
                ->on('institutions');
            
            $table->foreign('babysitter_id')
                ->references('uuid')
                ->on('users');
        });
        
        Schema::create('groups_users', function (Blueprint $table) {
            $table->uuid('id');
            $table->char('group_id', 36);
            $table->char('child_id', 36);
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');

            $table->foreign('group_id')
                ->references('id')
                ->on('groups');

            $table->foreign('child_id')
                ->references('uuid')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
        Schema::dropIfExists('groups_users');
    }
}
