<?php


namespace App\Repositories;


use App\Models\Finder\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use WebPConvert\Convert\Exceptions\ConversionFailedException;
use WebPConvert\WebPConvert;

/**
 * Конвертирует изображения в WebP
 * @package App\Repositories
 */
class WebpRepository
{
    /**
     * @param File $file
     */
    public static function convert(File $file)
    {
        if (!$file->isImage() && !config('image.convert_webp')) return;

        $files = collect([$file->fullName(), $file->thumbnail()]);
        $extension = $file->extension();

        foreach ($files as $item) {
            if (!Storage::exists($item)) continue;

            $file_convert = Storage::path($item);
            $destination = Str::replaceLast($extension, 'webp', $file_convert);

            try {
                WebPConvert::convert($file_convert, $destination, []);
                /* не удаляем оригинал, используем тег picture */
//                Storage::delete($item);

                /* update file data */
                if ($file->fullName() == $item) {
//                    $file->filename = basename($destination);
                    $file->size = Storage::size($file->fullName());
//                    $file->mimeType = Storage::mimeType($file->fullName());

                    list($image_x, $image_y) = getimagesize(Storage::path($file->fullName()));
                    $file->image_x = $image_x;
                    $file->image_y = $image_y;

                    $file->save();
                }

            } catch (ConversionFailedException $exception) {
                Log::warning($exception->getMessage());
            }
        }
    }

    /**
     * @param string $source
     * @param string $destination
     * @param int $quality
     * @return bool
     */
    public static function toJPEG(string $source, string $destination, int $quality = 90): bool
    {
        $image = imagecreatefromwebp(Storage::path($source));
        $width = imagesx($image);
        $height = imagesy($image);
        $new = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($new, 255, 255, 255);
        imagefilledrectangle($new, 0, 0, $width, $height, $background);
        imagecopyresampled($new, $image, 0, 0, 0, 0, $width, $height, $width, $height);
        imagejpeg($new, Storage::path($destination), $quality);

        if (Storage::exists($destination)) {
            return true;
        }

        return false;
    }
}
