<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LongMetaDescriptionAndTitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meta_translations', function (Blueprint $table) {
            $table->text('title')->change();
            $table->text('description')->change();
            $table->text('keywords')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meta_translations', function (Blueprint $table) {
            $table->string('title', 255)->change();
            $table->string('description', 255)->change();
            $table->string('keywords', 255)->change();
        });
    }
}
