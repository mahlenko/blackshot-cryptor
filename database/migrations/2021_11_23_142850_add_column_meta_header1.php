<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMetaHeader1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meta_translations', function (Blueprint $table) {
            $table->string('heading_h1', 255)
                ->after('keywords')
                ->nullable();
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
            $table->dropColumn('heading_h1');
        });
    }
}
