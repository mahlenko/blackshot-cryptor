<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PageDropMetaColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['view_as_catalog', 'is_active', 'template']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('template')->default('default');
            $table->boolean('view_as_catalog')->after('template')->default(false);
            $table->boolean('is_active')->default(true)->after('view_as_catalog');
        });
    }
}
