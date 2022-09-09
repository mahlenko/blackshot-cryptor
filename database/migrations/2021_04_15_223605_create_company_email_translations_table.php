<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyEmailTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_email_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('company_email_uuid');
            $table->string('locale', 4)->index();
            $table->string('description')->nullable();

            $table->unique(['company_email_uuid', 'locale']);
            $table->foreign('company_email_uuid')
                ->references('uuid')
                ->on('company_emails')
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
        Schema::dropIfExists('company_email_translations');
    }
}
