<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('page_uuid');
            $table->string('locale', 4)->index();
            $table->string('name');
            $table->string('description')->nullable();
            $table->mediumText('body')->nullable();
            $table->timestamps();

            $table->unique(['page_uuid', 'locale']);
            $table->foreign('page_uuid')
                ->references('uuid')
                ->on('pages')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_translations');
    }
}
