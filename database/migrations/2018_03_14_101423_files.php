<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Files extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedInteger('owner_id')->nullable();
            $table->string('title');
            $table->string('parent_type');
            $table->unsignedInteger('parent_id');
            $table->string('driver')->default('local');
            $table->string('file')->nullable();
            $table->text('meta_data');
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->index(['id', 'parent_type', 'parent_id']);

            $table->foreign('owner_id')
                ->references('id')
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
        Schema::dropIfExists('files');
    }
}
