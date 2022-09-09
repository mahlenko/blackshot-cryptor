<?php


namespace Anita\Entities\Navigation;


use App\Models\Finder\File;
use Illuminate\Support\Facades\Storage;

class Icon
{
    /**
     * @var string
     */
    public string $filename;

    /**
     * @var string
     */
    public string $thumbnail;

    /**
     * @var int
     */
    public int $image_x;

    /**
     * @var int
     */
    public int $image_y;

    /**
     * @var int
     */
    public int $size;

    /**
     * @var string|null
     */
    public ?string $mimetype;

    /**
     * @var string|null
     */
    public ?string $alt;

    /**
     * @var string|null
     */
    public ?string $title;

    /**
     * @var string|null
     */
    public ?string $description;

    /**
     * @var File|null
     */
    public ?File $file;

    /**
     * Icon constructor.
     * @param string|null $filename
     * @param string|null $folder
     * @param int|null $image_x
     * @param int|null $image_y
     * @param int|null $size
     * @param string|null $mimetype
     * @param string|null $alt
     * @param string|null $title
     * @param string|null $description
     * @param File|null $file
     */
    public function __construct(
        string $filename = null,
        string $folder = null,
        int $image_x = null,
        int $image_y = null,
        int $size = null,
        string $mimetype = 'null',
        string $alt = 'null',
        string $title = 'null',
        string $description = 'null',
        ?File $file = null
    ) {

        if (!$filename) return;

        $this->filename = Storage::url($folder . $filename);
        $this->thumbnail = Storage::url($folder .'thumbnails'. DIRECTORY_SEPARATOR . $filename);
        $this->image_x = intval($image_x);
        $this->image_y = intval($image_y);
        $this->size = intval($size);
        $this->mimetype = $mimetype;
        $this->alt = $alt;
        $this->title = $title;
        $this->description = $description;
        $this->file = $file;
    }

}
