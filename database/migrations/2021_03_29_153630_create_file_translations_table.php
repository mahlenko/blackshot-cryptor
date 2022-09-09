<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('alt');
            $table->dropColumn('description');
        });

        Schema::create('file_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 5)->default(config('app.locale'));
            $table->uuid('file_uuid');
            $table->string('alt')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->unique(['file_uuid', 'locale']);
            $table->foreign('file_uuid')
                ->on('files')
                ->references('uuid')
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
        Schema::table('files', function(Blueprint $table) {
            $table->string('alt')->nullable();
            $table->string('description')->nullable();
        });

        Schema::dropIfExists('file_translations');
    }
}
