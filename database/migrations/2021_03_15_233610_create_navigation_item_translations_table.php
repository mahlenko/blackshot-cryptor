<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationItemTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_item_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('navigation_item_uuid')->index();
            $table->string('locale', 4)->index();
            $table->string('name');
            $table->timestamps();

            $table->unique(['navigation_item_uuid', 'locale']);
            $table->foreign('navigation_item_uuid')
                ->references('uuid')
                ->on('navigation_items')
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
        Schema::dropIfExists('navigation_item_translations');
    }
}
