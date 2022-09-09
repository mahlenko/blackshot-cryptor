<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NavigationDataUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\App\Models\Navigation\NavigationItem::all() as $item) {
            if (!$item->object_uuid) continue;

            $meta = \App\Models\Meta::where(['parent_uuid' => $item->object_uuid])->first();

            if ($meta) {
                $item->meta_uuid = $meta->uuid;
                $item->url = $meta->url;
                $item->save();
            }
        }

        Schema::table('navigation_items', function (Blueprint $table) {
            $table->dropColumn('object_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->uuid('object_uuid')->index();
        });
    }
}
