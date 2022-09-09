<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('parent_uuid')->nullable()->index()->comment('Объект которому принадлежит файл');
            $table->uuid('folder_uuid')->nullable();
            $table->string('name');
            $table->string('filename', 255);
            $table->string('mimeType', 50);
            $table->integer('size')->unsigned();
            $table->integer('downloads')->default(0)->unsigned();
            $table->integer('image_x')->unsigned()->nullable();
            $table->integer('image_y')->unsigned()->nullable();
            $table->string('alt')->nullable();
            $table->string('description')->nullable();
            $table->nestedSet(); // sortable
            $table->timestamps();
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
