<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_features', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('product_uuid');
            $table->uuid('feature_uuid');
            $table->uuid('feature_variant_uuid')->nullable();

            $table->unique(['product_uuid', 'feature_uuid', 'feature_variant_uuid'], 'full_features_product_uuid_unique');

            $table->foreign('product_uuid')
                ->on('products')
                ->references('uuid')
                ->onDelete('cascade');

            $table->foreign('feature_uuid')
                ->on('features')
                ->references('uuid')
                ->onDelete('cascade');

            $table->foreign('feature_variant_uuid')
                ->on('feature_variants')
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
        Schema::dropIfExists('product_features');
    }
}
