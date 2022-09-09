<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPhoneTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_phone_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('company_phone_uuid');
            $table->string('locale', 4)->index();
            $table->string('description')->nullable();

            $table->unique(['company_phone_uuid', 'locale']);
            $table->foreign('company_phone_uuid')
                ->references('uuid')
                ->on('company_phones')
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
        Schema::dropIfExists('company_phone_translations');
    }
}
