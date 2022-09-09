<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_phones', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('company_uuid')->index();
            $table->integer('country_code')->nullable();
            $table->string('number')->nullable();
            $table->nestedSet();
            $table->timestamps();

            $table->unique(['company_uuid', 'country_code', 'number']);
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
        Schema::dropIfExists('company_phones');
    }
}
