<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('product_uuid')->index();
            $table->uuid('category_uuid');
            $table->nestedSet();

            $table->unique(['product_uuid', 'category_uuid']);

            $table->foreign('product_uuid')
                ->references('uuid')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('category_uuid')
                ->references('uuid')
                ->on('categories')
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
        Schema::dropIfExists('product_categories');
    }
}
