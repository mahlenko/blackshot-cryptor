<?php


namespace Anita\Entities\Navigation;


use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class Item
 * @package Anita\Entities\Navigation
 */
class Item
{
    /**
     * Прятать адрес домена для внутренних ссылок
     */
    const HIDDEN_HOST = true;

    /**
     * @var string
     */
    public string $uuid;

    /**
     * @var string
     */
    public string $text;

    /**
     * @var string
     */
    public string $url;

    /**
     * @var string|null
     */
    public ?string $title;

    /**
     * @var bool
     */
    public bool $external = false;

    /**
     * @var string|null
     */
    public ?string $target;

    /**
     * @var string|null
     */
    public ?string $type;

    /**
     * @var string|null
     */
    public ?string $css;

    /**
     * @var string|null
     */
    public ?string $style;

    /**
     * @var string|null
     */
    public ?string $template;

    /**
     * @var int
     */
    public int $depth = -1;

    /**
     * @var Icon|null
     */
    public ?Icon $icon;

    /**
     * @var Collection|null
     */
    public ?Collection $children;

    /**
     * Item constructor.
     * @param string $uuid
     * @param string $text
     * @param string $url
     * @param string $title
     * @param string $target
     * @param string $type
     * @param string $style
     * @param string $css
     * @param string $template
     * @param int $depth
     * @param Icon $icon ,
     * @param Collection $children
     */
    public function __construct(
        string $uuid,
        string $text,
        string $url,
        string $title,
        string $target,
        string $type,
        string $style,
        string $css,
        string $template,
        int $depth,
        Icon $icon,
        Collection $children
    ) {
        $this->uuid = $uuid;
        $this->text = $text;
        $this->url = $this->generateUrl($url);
        $this->title = $title ?: null;
        $this->target = $target ?: null;
        $this->type = Str::lower($type) ?: null;
        $this->css = $css ?: null;
        $this->style = $style ?: null;
        $this->template = $template ?: null;
        $this->depth = $depth;
        $this->icon = isset($icon->filename) ? $icon : null;
        $this->children = $children->count() ? $children : null;
    }

    /**
     * @param string $url
     * @return string
     */
    private function generateUrl(string $url): string
    {
        if (empty($url) || $url == '/') return route('home');

        $parse_url = parse_url($url);

        if (!key_exists('scheme', $parse_url)) {
            $url = route('view', ['slug' => $url]);

            if (self::HIDDEN_HOST) {
                $url = parse_url($url);

                return !empty($url['query'])
                    ? $url['path'] .'?'. $url['query']
                    : $url['path'];
            }
        }

        $this->external = true;
        return $url;
    }
}
