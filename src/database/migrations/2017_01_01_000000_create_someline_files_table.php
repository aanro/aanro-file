<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSomelineFilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('someline_files', function (Blueprint $table) {
            $table->increments('someline_file_id');
            $table->unsignedInteger('user_id')->index()->nullable();

            // Adding more table related fields here...
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('guess_extension')->nullable();
            $table->string('original_name')->nullable();
            $table->string('original_extension')->nullable();
            $table->string('original_mime_type')->nullable();
            $table->char('file_sha1', 40);
            $table->unsignedInteger('file_size');

            $table->softDeletes();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->ipAddress('created_ip')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->ipAddress('updated_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('someline_files');
    }

}
