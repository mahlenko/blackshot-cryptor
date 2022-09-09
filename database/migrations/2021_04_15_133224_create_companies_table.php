<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('type');
            $table->string('slug');
            $table->nestedSet();
            $table->timestamps();
        });

        Schema::table('companies', function(Blueprint $table) {
            $table->uuid('parent_id', 36)->change();
            $table->foreign('parent_id')
                ->references('uuid')
                ->on('companies')
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
        Schema::dropIfExists('companies');
    }
}
