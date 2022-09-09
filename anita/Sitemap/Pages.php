<?php


namespace Anita\Sitemap;


use Anita\Entities\SitemapItem;
use Anita\Interfaces\SitemapAbstract;
use Exception;
use Illuminate\Support\Collection;

class Pages extends SitemapAbstract
{
    /**
     * @param string $url
     * @param string $lastmod
     * @param float $priority
     * @param string $changefreq
     * @param Collection|null $alternative
     * @return SitemapItem
     * @throws Exception
     */
    public static function item(
        string $url,
        string $lastmod,
        float  $priority = self::PRIORITY,
        string $changefreq = self::CHANGE_FREQ,
        Collection $alternative = null
    ): SitemapItem {
        if ($url == '/') $priority = 1.0;

        return parent::item($url, $lastmod, $priority, $changefreq, $alternative);
    }
}
