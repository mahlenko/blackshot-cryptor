<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationItemParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_item_params', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('style')->nullable();
            $table->string('css')->nullable();
            $table->string('iconCss')->nullable();
            $table->string('target')->default('self');
            $table->timestamps();

            $table->foreign('uuid') // удалять параметры ссылки, если ссылка была удалена
                ->references('uuid')
                ->on('navigation_items')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigation_item_params');
    }
}
