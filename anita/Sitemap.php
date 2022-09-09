<?php


namespace Anita;

use Exception;
use Anita\Interfaces\SitemapAbstract;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

/**
 * Class Sitemap
 * @package Anita
 * @see https://yandex.ru/support/webmaster/controlling-robot/sitemap.html#sitemap__requirements
 * @see https://yandex.ru/support/webmaster/controlling-robot/sitemap-local.html#sitemap-local
 */
class Sitemap
{
    /**
     * @param array $types
     * @return Response
     * @throws Exception
     */
    public static function generate(array $types = []): Response
    {
        $urls = collect();

        foreach ($types as $type) {
            $name = Str::plural(class_basename($type));

            /* @var SitemapAbstract $classname */
            $classname = self::class .'\\'. $name;

            if (class_exists($classname)) {
                foreach (($classname::build(new $type))->items as $group) {
                    $alternative = collect();

                    if ($group->count() > 1) {
                        $item = $group->where('locale', config('translatable.locale'))->first();
                        $alternative = $group->where('locale', '<>', config('translatable.locale'));
                    } else {
                        $item = $group->first();
                    }

                    $urls->add(
                        $classname::item(
                            $item->url,
                            $item->updated_at ?? $item->created_at,
                            $classname::PRIORITY,
                            $classname::CHANGE_FREQ,
                            $alternative
                        )
                    );
                }
            }
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>';

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-type', 'application/xml; charset=utf-8');
    }

}
