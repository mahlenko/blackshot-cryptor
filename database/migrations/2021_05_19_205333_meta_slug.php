<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MetaSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metas', function (Blueprint $table) {
//            $table->string('slug')->after('uuid');
            $table->dropColumn(['class', 'parent_uuid']);
        });

        $metas = \App\Models\Meta::get()
            ->sortBy('object._lft')
            ->groupBy('object_type');

        if ($metas) {
            foreach ($metas as $group) {
                foreach ($group as $meta) {
                    /* создадим slugs из ссылки */
                    $segments = collect(explode('/', $meta->url));
                    $slug = $segments->last();

                    if ($segments->count() > 1) {
                        $bug_start_with_slug = $segments->splice(0, -1)->join('');
                        if (\Illuminate\Support\Str::startsWith($slug, $bug_start_with_slug)) {
                            $slug = \Illuminate\Support\Str::replace($bug_start_with_slug, '', $slug);
                        }
                    }

                    $meta->slug = trim($slug);
                    $meta->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metas', function (Blueprint $table) {
//            $table->dropColumn('slug');
            $table->uuid('parent_uuid')->index();
            $table->string('class')->nullable(true)->after('parent_uuid');
        });
    }
}
