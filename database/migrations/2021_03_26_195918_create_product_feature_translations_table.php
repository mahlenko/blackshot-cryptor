<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductFeatureTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_feature_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('product_feature_uuid')->index();
            $table->string('locale', 5)->default(config('app.locale'));
            $table->text('value')->nullable();

            $table->unique(['product_feature_uuid', 'locale']);
            $table->foreign('product_feature_uuid')
                ->references('uuid')
                ->on('product_features')
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
        Schema::dropIfExists('product_feature_translations');
    }
}
