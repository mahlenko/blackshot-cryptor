<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureGroupTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_group_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('feature_group_uuid');
            $table->string('locale',5);
            $table->string('name');
            $table->text('body')->nullable();

            $table->unique(['feature_group_uuid', 'locale']);
            $table->foreign('feature_group_uuid')
                ->references('uuid')
                ->on('feature_groups')
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
        Schema::dropIfExists('feature_group_translations');
    }
}
