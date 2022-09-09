<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyAddressTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_address_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 4)->index();
            $table->uuid('company_address_uuid');
            $table->string('value')->nullable();

            $table->unique(['company_address_uuid', 'locale']);
            $table->foreign('company_address_uuid')
                ->references('uuid')
                ->on('company_addresses')
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
        Schema::dropIfExists('company_address_translations');
    }
}
