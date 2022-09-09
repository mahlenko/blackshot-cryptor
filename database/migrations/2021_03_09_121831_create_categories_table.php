<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('slug');
            $table->string('template')->default('default');
            $table->nestedSet();
            $table->timestamps();
        });

        Schema::table('categories', function(Blueprint $table) {
            $table->uuid('parent_id', 36)->change();
            $table->foreign('parent_id')
                ->references('uuid')
                ->on('categories')
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
        Schema::dropIfExists('categories');
    }
}
