<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureVariantTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_variant_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('feature_variant_uuid')->index();
            $table->string('locale', 5)->default(config('app.locale'));
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('body')->nullable();

            $table->unique(['feature_variant_uuid', 'locale']);
            $table->foreign('feature_variant_uuid')
                ->references('uuid')
                ->on('feature_variants')
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
        Schema::dropIfExists('feature_variant_translations');
    }
}
