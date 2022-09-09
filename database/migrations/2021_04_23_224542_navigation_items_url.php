<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NavigationItemsUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->string('url')->nullable(true)->after('uuid');
            $table->uuid('meta_uuid')->nullable(true)->after('url');

            $table->dropColumn('class_name');
        });

        Schema::table('navigation_items', function (Blueprint $table) {
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
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->dropForeign('navigation_items_meta_uuid_foreign');
            $table->dropColumn(['url', 'meta_uuid']);
            $table->string('class_name');
        });
    }
}
