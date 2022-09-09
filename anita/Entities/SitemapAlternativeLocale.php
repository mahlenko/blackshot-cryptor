<?php


namespace Anita\Entities;

use Illuminate\Support\Str;

/**
 * Class SitemapAlternativeLocale
 * @package Anita\Entities
 */
class SitemapAlternativeLocale
{
    /**
     * @var string
     */
    public string $rel = 'alternate';

    /**
     * @var string
     */
    public string $hreflang;

    /**
     * @var string
     */
    public string $href;

    /**
     * SitemapAlternativeLocale constructor.
     * @param string $locale
     * @param string $href
     */
    public function __construct(string $locale, string $href)
    {
        $this->hreflang = $locale;
        $this->href = route('view', ['slug' => $href]);

        /*  */
        $default_locale = config('translatable.locale');
        if ($default_locale != $locale) {
            $this->href = Str::replaceFirst('/'. $default_locale .'/', '/'. $locale .'/', $this->href);
        }

    }
}
