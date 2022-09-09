<?php

namespace App\Models;

use Carbon\CarbonInterval;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

class Video extends Model implements TranslatableContract
{
    use HasFactory, Translatable, NodeTrait;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string[]
     */
    protected $fillable = ['type', 'url', 'thumbnail_url', 'width', 'width_unit', 'height', 'height_unit', 'duration'];

    /**
     * @var array
     */
    public $translatedAttributes = ['name', 'description'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Вернет HTML IFRAME для вставки на страницу
     */
    public function html(bool $use_thumbnail = false)
    {
        return sprintf(
            '<iframe class="video-embed" src="%s" %s data-type="%s" allowfullscreen frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" style="max-width: %s;"></iframe>',
            $this->url,
            $use_thumbnail ? $this->sizeThumbnail : $this->size,
            $this->type,
            '100%'
        );
    }

    /**
     * Получит информацию о видео через сервис Vimeo
     * @return mixed|void|null
     */
    public function getVimeoData()
    {
        $path = parse_url($this->url, PHP_URL_PATH);
        $path_segments = explode('/', $path);

        $video_id = $path_segments[count($path_segments) - 1];

        $api_data = file_get_contents('https://vimeo.com/api/v2/video/'. $video_id .'.json');
        if (!$api_data || empty($api_data)) return;

        $api_data = json_decode($api_data);
        if (json_last_error()) {
            return;
        }

        return $api_data[0] ?? null;
    }

    /**
     * Обновит данные видео из Vimeo
     */
    public function updateVimeoData()
    {
        $vimeo = $this->getVimeoData();
        if (!$vimeo) return;

        $this->name = Str::limit($vimeo->title, 255);
        $this->description = Str::limit($vimeo->description, 255);
        $this->uploaded_at = $vimeo->upload_date;
        $this->thumbnail_url = $vimeo->thumbnail_large;
        $this->views = $vimeo->stats_number_of_plays;
        $this->duration = $vimeo->duration;
        $this->attributes['width'] = $vimeo->width;
        $this->attributes['height'] = $vimeo->height;
    }

    /**
     * @return mixed|void|null
     */
    public function getYoutubeData()
    {
        $path = parse_url($this->url, PHP_URL_PATH);
        $path_segments = explode('/', $path);

        $video_id = $path_segments[count($path_segments) - 1];

        $api_url = 'https://www.googleapis.com/youtube/v3/videos';
        $api_data = file_get_contents($api_url .'?'. http_build_query([
            'id' => $video_id,
            'key' => env('YOUTUBE_API_KEY'),
            'part' => implode(',', [
                'snippet', 'statistics', 'contentDetails',
            ])
        ]));

        if (!$api_data || empty($api_data)) return;

        $api_data = json_decode($api_data);
        if (json_last_error() || isset($api_data->error)) {
            return;
        }

        return $api_data->items[0] ?? null;
    }

    /**
     *
     */
    public function updateYoutubeData()
    {
        $youtube = $this->getYoutubeData();
        if (!$youtube) return;

        $this->name = Str::limit($youtube->snippet->title, 250);
        $this->description = Str::limit($youtube->snippet->description, 250);
        $this->uploaded_at = Carbon::parse($youtube->snippet->publishedAt)->format('Y-m-d H:i:s');
        $this->thumbnail_url = $youtube->snippet->thumbnails->standard->url;
        $this->views = $youtube->statistics->viewCount;
        $this->duration = CarbonInterval::parseFromLocale($youtube->contentDetails->duration)->totalSeconds;
    }

    /**
     * @return string
     * @throws \Exception
     * @return string
     */
    public function jsonLd(): string
    {
        return '<script type="application/ld+json">' . json_encode(array_filter([
            "@context" => "https://schema.org",
            "@type" => "VideoObject",
            "name" => $this->name,
            "description" => $this->description ?: $this->name,
            "thumbnailUrl" => array_filter([
                $this->thumbnail_url ?? null
            ]),
            "uploadDate" => (new DateTimeImmutable($this->uploaded_at ?? $this->created_at))->format('c'),
            "duration" => CarbonInterval::seconds($this->duration)->spec(),
            "embedUrl" => $this->url,
        ])) . '</script>';
    }

    /**
     * @param string $value
     */
    public function setUrlAttribute(string $value = null)
    {
        $parse_url = parse_url($value);

        $video_iframe_url = null;

        /* youtube detect */
        if (preg_match('/(youtu)\.?(be|com)/', $parse_url['host'])) {
            /*  */
            $this->attributes['type'] = 'youtube';

            /*  */
            $video_id = null;

            /* youtube */
            if (key_exists('query', $parse_url)) {
                parse_str($parse_url['query'], $query);
                if (key_exists('v', $query)) {
                    $video_id = $query['v'];
                }
            }

            if (!$video_id) {
                $path_segment = array_values(
                    array_filter(
                        explode('/', $parse_url['path'])
                    )
                );

                if ($path_segment) {
                    $video_id = $path_segment[count($path_segment) - 1];
                }
            }

            if ($video_id) {
                $video_iframe_url = 'https://www.youtube.com/embed/' . $video_id;
            }
        }

        /* vimeo detect */
        if (preg_match('/vimeo\.com/', $parse_url['host'])) {
            /*  */
            $this->attributes['type'] = 'vimeo';

            /*  */
            $video_id = null;

            $path_segment = array_values(
                array_filter(
                    explode('/', $parse_url['path'])
                )
            );

            if ($path_segment) {
                $video_id = $path_segment[count($path_segment) - 1];
            }

            if ($video_id) {
                $video_iframe_url = 'https://player.vimeo.com/video/' . $video_id;
            }
        }

        $this->attributes['url'] = $video_iframe_url ?? $value;
    }

    /**
     * @return int|mixed
     */
    public function setWidthAttribute($value)
    {
        if (intval($value) <= 0) $this->attributes['width'] = 640;
        else $this->attributes['width'] = intval($value);
    }

    /**
     * @return int|mixed
     */
    public function setHeightAttribute($value)
    {
        if (intval($value) <= 0) $this->attributes['height'] = 360;
        else $this->attributes['height'] = intval($value);
    }

    /**
     * @return string
     */
    public function getSizeAttribute()
    {
        return 'width="'. $this->attributes['width'] .'" height="'. $this->attributes['height'] .'"';
    }

    /**
     * @return string
     */
    public function getSizeThumbnailAttribute()
    {
        return 'width="200px" height="150px"';
    }

    /**
     * @return string[]
     */
    protected function getScopeAttributes()
    {
        return ['parent_uuid'];
    }

    protected static function booted()
    {
        self::creating(function($video) {
            /* @var Video $video */
            if ($video->type == 'vimeo') {
                $video->updateVimeoData();
            } elseif ($video->type == 'youtube') {
                $video->updateYoutubeData();
            }
        });
    }
}
