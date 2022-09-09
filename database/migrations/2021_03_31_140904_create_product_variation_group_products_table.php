<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationGroupProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variation_group_products', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('group_uuid');
            $table->uuid('product_uuid');
            $table->uuid('parent_product_uuid')->nullable();

            $table->unique(['group_uuid', 'product_uuid']);
            $table->timestamps();

            $table->foreign('group_uuid')
                ->on('product_variation_groups')
                ->references('uuid')
                ->onDelete('cascade');

            $table->foreign('product_uuid')
                ->on('products')
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
        Schema::dropIfExists('product_variation_group_products');
    }
}
