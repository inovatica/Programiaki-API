<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->char('child_id', 36);
            $table->dateTime('issued_at');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('uuid');
            $table->boolean('active')->default(1);
            
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
        Schema::dropIfExists('certifications');
    }
}
