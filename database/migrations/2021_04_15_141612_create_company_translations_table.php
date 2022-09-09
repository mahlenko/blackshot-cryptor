<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('company_uuid')->index();
            $table->string('locale', 4)->index();
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('body')->nullable();

            $table->unique(['company_uuid', 'locale']);
            $table->foreign('company_uuid')
                ->references('uuid')
                ->on('companies')
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
        Schema::dropIfExists('company_translations');
    }
}
