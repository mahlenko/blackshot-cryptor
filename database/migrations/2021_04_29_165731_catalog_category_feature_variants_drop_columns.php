<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CatalogCategoryFeatureVariantsDropColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feature_variants', function (Blueprint $table) {
            $table->dropColumn(['slug', 'views']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feature_variants', function (Blueprint $table) {
            $table->string('slug')->nullable()->index();
            $table->bigInteger('views')->default(0);
        });
    }
}
