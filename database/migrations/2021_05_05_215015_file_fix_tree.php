<?php

use App\Models\Finder\File;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FileFixTree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $files = \App\Models\Finder\File::all();

        $fileFixedClone = collect();
        foreach ($files as $file) {
            $fileFixes = $file->replicate();
            $fileFixes->uuid = $file->uuid;
            $fileFixedClone->push($fileFixes);
            $file->delete();
        }

        foreach ($fileFixedClone as $file) {
            $file->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
