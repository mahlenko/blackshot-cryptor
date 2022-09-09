<?php


namespace Anita\Entities;


use DateTimeImmutable;
use Illuminate\Support\Collection;

/**
 * Элемент карты сайта
 * @package Anita\Entities
 */
class SitemapItem
{
    const DATE_FORMAT = 'Y-m-d';

    /**
     * @var string
     */
    public string $loc;

    /**
     * @var string
     */
    public string $lastmod;

    /**
     * @var string
     */
    public string $changefreq;

    /**
     * @var float
     */
    public float $priority;

    /**
     * @var Collection|null
     */
    public ?Collection $alternative;

    /**
     * @throws \Exception
     */
    public function __construct(
        string $loc,
        string $lastmod,
        string $changefreq,
        float $priority,
        Collection $alternative = null
    ) {
        $this->loc = route('view', ['slug' => $loc]);
        $this->lastmod = (new DateTimeImmutable($lastmod))->format(self::DATE_FORMAT);
        $this->changefreq = $changefreq;
        $this->priority = $priority;
        $this->alternative = $alternative;
    }
}
