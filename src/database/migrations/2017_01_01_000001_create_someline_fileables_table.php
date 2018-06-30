<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSomelineFileablesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('someline_fileables', function(Blueprint $table) {
//            $table->increments('someline_fileable_id');
//            $table->unsignedInteger('user_id')->index();

            // Adding more table related fields here...
            $table->morphs('fileable', 'someline_fileable_index');
            $table->unsignedInteger('someline_file_id')->index();
            $table->string('original_name')->nullable();
            $table->string('type')->nullable();
            $table->json('data')->nullable();

//            $table->unsignedInteger('created_by')->nullable();
//            $table->timestamp('created_at')->nullable();
//            $table->ipAddress('created_ip')->nullable();
//            $table->unsignedInteger('updated_by')->nullable();
//            $table->timestamp('updated_at')->nullable();
//            $table->ipAddress('updated_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('someline_fileables');
    }

}
