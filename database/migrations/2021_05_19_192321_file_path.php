<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FilePath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('folder_path')->after('folder_uuid');
        });

        $files = \App\Models\Finder\File::with('folder')->get();
        if ($files) {
            /* @var \App\Models\Finder\File $file */
            foreach ($files as $file) {
                $file->generatePath();
                $file->save();
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
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('folder_path');
        });
    }
}
