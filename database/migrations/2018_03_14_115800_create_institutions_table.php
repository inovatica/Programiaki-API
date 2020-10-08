<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('city');
            $table->string('zip_code');
            $table->string('street');
            $table->string('street_number');
            $table->string('phone');
            $table->timestamps();
            $table->softDeletes();
            
            $table->primary('id');
        });
        
        Schema::create('institutions_users', function (Blueprint $table) {
            $table->uuid('id');
            $table->char('institution_id', 36);
            $table->char('user_id', 36);
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');

            $table->foreign('institution_id')
                ->references('id')
                ->on('institutions');

            $table->foreign('user_id')
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
        Schema::dropIfExists('institutions');
        Schema::dropIfExists('institutions_users');
    }
}
