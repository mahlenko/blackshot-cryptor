<?php

namespace App\Http\Controllers;

use App\Models\Finder\File;
use App\Repositories\WebpRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConvertJpg extends Controller
{
    public function index()
    {
        /* @var File $image */
        $images = File::where('mimeType', 'image/webp')
            ->where('filename', 'like', '%.webp');

        dump('Осталось: ' . $images->count());
        $image = $images->first();

        if (!$image) dd('All images convert.');

        $destination = Str::replace('.webp', '.jpg', $image->fullName());
        $destination_thumb = Str::replace('.webp', '.jpg', $image->thumbnail());

        if (Storage::exists($image->fullName())) {
            if (WebpRepository::toJPEG($image->fullName(), $destination)) {
                if (!Storage::exists($image->thumbnail())) {
                    $clone = clone $image;
                    $clone->filename = basename($destination);

                    File::createThumbnail($clone);
                    WebpRepository::convert($clone);
                } else {
                    WebpRepository::toJPEG($image->thumbnail(), $destination_thumb);
                }

                /* сохраним новый формат изображения */
                $image->mimeType = mime_content_type(Storage::path($destination));
                $image->filename = basename($destination);
                $image->size = filesize(Storage::path($destination));
                $image->save();
            }
        }

        echo $image->fullName();
        echo '<script>setTimeout(function() { window.location.reload() }, 1000);</script>';

    }
}
