<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MetaAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metas', function (Blueprint $table) {
            $table->string('url')->nullable(true)->after('uuid');
            $table->string('class')->nullable(true)->after('parent_uuid');
            $table->bigInteger('views')->default(0)->after('robots');
            $table->boolean('is_active')->default(true)->after('views');
            $table->timestamp('publish_at')->default(DB::raw('CURRENT_TIMESTAMP'))->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metas', function (Blueprint $table) {
            $table->dropColumn(['url', 'class', 'views', 'is_active', 'publish_at']);
        });
    }
}
