<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MetasTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metas', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('description');
            $table->dropColumn('keywords');
        });

        Schema::create('meta_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('meta_uuid');
            $table->string('locale', 5)->index();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->timestamps();

            $table->unique(['meta_uuid', 'locale']);
            $table->foreign('meta_uuid')
                ->references('uuid')
                ->on('metas')
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
        Schema::table('metas', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
        });

        Schema::dropIfExists('meta_translations');
    }
}
