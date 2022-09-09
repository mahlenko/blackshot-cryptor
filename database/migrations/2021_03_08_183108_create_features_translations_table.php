<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturesTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('feature_uuid')->index();
            $table->string('locale', 5)->default(config('app.locale'));
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('prefix')->nullable();
            $table->string('postfix')->nullable();

            $table->unique(['feature_uuid', 'locale']);
            $table->foreign('feature_uuid')
                ->references('uuid')
                ->on('features')
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
        Schema::dropIfExists('feature_translations');
    }
}
