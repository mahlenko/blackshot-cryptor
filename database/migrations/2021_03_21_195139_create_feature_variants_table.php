<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_variants', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('feature_uuid');
            $table->string('slug')->nullable();
            $table->string('url')->nullable();
            $table->string('color')->nullable();
            $table->bigInteger('views')->unsigned()->default(0);
            $table->nestedSet();
            $table->timestamps();
        });

        Schema::table('feature_variants', function(Blueprint $table) {
            $table->uuid('parent_id', 36)->change();
            $table->foreign('feature_uuid')
                ->references('uuid')
                ->on('features')
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
        Schema::dropIfExists('feature_variants');
    }
}
