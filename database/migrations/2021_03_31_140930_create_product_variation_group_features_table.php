<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationGroupFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variation_group_features', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('group_uuid');
            $table->uuid('feature_uuid');
            $table->string('purpose');

            $table->unique(['group_uuid', 'feature_uuid']);
            $table->timestamps();

            $table->foreign('group_uuid')
                ->on('product_variation_groups')
                ->references('uuid')
                ->onDelete('cascade');

            $table->foreign('feature_uuid')
                ->on('features')
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
        Schema::dropIfExists('product_variation_group_features');
    }
}
