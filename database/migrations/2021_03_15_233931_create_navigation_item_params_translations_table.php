<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationItemParamsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_item_param_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('navigation_item_param_uuid')->index('nav_item_param_uuid');
            $table->string('locale', 4)->index();
            $table->string('title')->nullable();
            $table->string('iconAlt')->nullable();
            $table->timestamps();

            $table->unique(['navigation_item_param_uuid', 'locale'], 'items_unique');
            $table->foreign('navigation_item_param_uuid', 'items_foreign')
                ->references('uuid')
                ->on('navigation_item_params')
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
        Schema::dropIfExists('navigation_item_param_translations');
    }
}
