<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('feature_uuid')->index();
            $table->uuid('category_uuid');

            $table->foreign('feature_uuid')
                ->references('uuid')
                ->on('features')
                ->onDelete('cascade');

            $table->foreign('category_uuid')
                ->references('uuid')
                ->on('categories')
                ->onDelete('cascade');

            $table->unique(['feature_uuid', 'category_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feature_categories');
    }
}
