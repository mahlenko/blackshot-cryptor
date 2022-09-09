<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('feature_group_uuid')->nullable();
            $table->string('slug')->nullable()->index();
            $table->string('purpose', 20)->comment('Цель, поиск через фильтр, вариант товара, бренд, доп. информация.');
            $table->string('view_product', 20)->comment('Внешний вид в товаре, выпадающий список, изображения (главное изображение варианта), текстовые метки');
            $table->string('view_filter', 20)->nullable()->comment('Вид в фильтре: флажок, слайдер с числами, цвет');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_show_feature')->default(false)->comment('Показываеть в характеристиках');
            $table->boolean('is_show_description')->default(false)->comment('Показывать в списке товаров');
            $table->nestedSet();
            $table->timestamps();
        });

        Schema::table('features', function(Blueprint $table) {
            $table->uuid('parent_id', 36)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('features');
    }
}
