<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MetaMorphObject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metas', function(Blueprint $table) {
            $table->uuidMorphs('object');
            $table->string('slug')->after('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metas', function(Blueprint $table) {
            $table->dropMorphs('object');
            $table->dropColumn('slug');
        });
    }
}
