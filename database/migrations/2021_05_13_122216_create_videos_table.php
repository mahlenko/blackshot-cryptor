<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('parent_uuid')->nullable()->index();
            $table->string('type')->nullable();
            $table->string('url');
            $table->string('thumbnail_url')->nullable();
            $table->integer('width')->default(0);
            $table->string('width_unit')->default('px');
            $table->integer('height')->default(0);
            $table->string('height_unit')->default('px');
            $table->integer('duration')->default(0);
            $table->integer('views')->default(0);
            $table->nestedSet();
            $table->timestamp('uploaded_at')->nullable();
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
        Schema::dropIfExists('videos');
    }
}
