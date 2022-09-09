<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('slug');
            $table->string('product_code', 64)->nullable();
            $table->decimal('price', 12, 2)->unsigned()->default(0.00);
            $table->integer('quantity')->unsigned();
            $table->decimal('weight', 12, 3)->unsigned()->default(0);
            $table->integer('length')->unsigned()->nullable();
            $table->integer('width')->unsigned()->nullable();
            $table->integer('height')->unsigned()->nullable();
            $table->integer('min_qty')->unsigned()->default(1);
            $table->integer('max_qty')->unsigned()->default(0);
            $table->integer('step_qty')->unsigned()->default(1);
            $table->integer('age_limit')->unsigned()->nullable()->comment('Минимальный возраст');
            $table->boolean('age_verification')->default(false)->comment('Требовать подтверждение возраста');
            $table->string('out_of_stock_action', 20)->default('disabled');
            $table->boolean('is_active')->default(true);
            $table->bigInteger('views')->default(0);
            $table->bigInteger('popular')->default(0);
            $table->timestamp('publish_at')->useCurrent();
            $table->nestedSet();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
