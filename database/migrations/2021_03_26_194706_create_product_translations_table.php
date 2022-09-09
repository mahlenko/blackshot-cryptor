<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('product_uuid')->index();
            $table->string('locale', 5)->default(config('app.locale'));
            $table->string('name');
            $table->text('body')->nullable();

            $table->unique(['product_uuid', 'locale']);
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
        Schema::dropIfExists('product_translations');
    }
}
