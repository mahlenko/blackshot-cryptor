<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('navigation_uuid')->index();
            $table->string('class_name');
            $table->string('object_uuid')->default('uuid');
            $table->boolean('is_active')->default(true);
            $table->nestedSet();
            $table->timestamps();
        });

        Schema::table('navigation_items', function(Blueprint $table) {
            $table->uuid('parent_id', 36)->change();
            $table->foreign('parent_id')
                ->references('uuid')
                ->on('navigation_items')
                ->cascadeOnDelete();

            $table->foreign('navigation_uuid')
                ->references('uuid')
                ->on('navigations')
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
        Schema::dropIfExists('navigation_items');
    }
}
