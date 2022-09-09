<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyTimeworkTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_timework_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('company_timework_uuid')->index();
            $table->string('locale', 4)->index();
            $table->string('value')->nullable();

            $table->unique(['company_timework_uuid', 'locale'], 'company_timework_locale_unique');
            $table->foreign('company_timework_uuid')
                ->references('uuid')
                ->on('company_timeworks')
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
        Schema::dropIfExists('company_timework_translations');
    }
}
