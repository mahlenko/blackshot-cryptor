<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('slug', 255);
            $table->timestamp('publish_at')->nullable();
            $table->bigInteger('views')->default(0);
            $table->string('template')->default('default');
            $table->nestedSet();
            $table->timestamps();
        });

        Schema::table('pages', function(Blueprint $table) {
            $table->uuid('parent_id', 36)->change();
            $table->foreign('parent_id')
                ->references('uuid')
                ->on('pages')
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
        Schema::dropIfExists('pages');
    }
}
