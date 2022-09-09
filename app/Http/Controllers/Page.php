<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use DateTimeImmutable;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Throwable;

class Page extends Controller
{
    /**
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index()
    {
        return $this->view('/');
    }

    /**
     * @param string $slug
     * @return Application|Factory|View
     * @throws Exception
     */
    public function view(string $slug)
    {
        $page = self::getPageData($slug);
        if (!$page) abort(404);

        /* показывать страницу как каталог */
        if ($page['meta']->show_nested) {
            $class_basename = Str::lower(class_basename($page['meta']->object_type));
            $table_trans = $class_basename .'_translations';
            $table_trans_key = $class_basename .'_uuid';

            /* uuid's категорий */
            $children_categories_uuid = $page['meta']->object_type::select('parent_id')
                ->withDepth()
                ->having('depth', '=', $page['data']->depth + 2)
                ->descendantsOf($page['data']->uuid)
                ->groupBy('parent_id')->keys();

            /* получим категории */
            $categories = collect();
            if ($children_categories_uuid && $children_categories_uuid->count()) {
                $categories = $page['meta']->object_type::whereIn('uuid', $children_categories_uuid)
                    ->join($table_trans, $table_trans.'.'. $table_trans_key, '=', Str::plural($class_basename).'.uuid')
                    ->where($table_trans .'.locale', app()->getLocale())
                    ->with(array_filter([
                        'meta',
                        method_exists($page['meta']->object_type, 'images') ? 'images' : null,
                        method_exists($page['meta']->object_type, 'images') ? 'images.folder' : null,
                        method_exists($page['meta']->object_type, 'icons') ? 'icons' : null,
                    ]))->orderBy('_lft')->get();

            }

            /* страницы внутри категории */
            $children = $page['meta']->object_type::whereIn(
                'parent_id',
                $categories->pluck('uuid')->push($page['data']->uuid)
            )
                ->select([Str::plural($class_basename) .'.*'])
                ->join($table_trans, $table_trans.'.'. $table_trans_key, '=', Str::plural($class_basename).'.uuid')
                ->where($table_trans .'.locale', app()->getLocale())
                ->whereNotIn('uuid', $categories->pluck('uuid'))
                ->with(array_filter([
                    'meta',
                    method_exists($page['meta']->object_type, 'images') ? 'images' : null,
                    method_exists($page['meta']->object_type, 'images') ? 'images.folder' : null,
                    method_exists($page['meta']->object_type, 'icons') ? 'icons' : null,
                ]));
        }

        return view($page['meta']->template, [
            'meta' => $page['meta'],
            'page' => $page['data'],
            'page_breadcrumbs' => $page['data'],
            'children' => isset($children) ? $children->orderBy('created_at', 'desc')->paginate() : null,
            'children_categories' => $categories ?? collect()
        ]);
    }

    /**
     * Получим данные страницы
     * @param string $slug
     * @return array|false
     * @throws Exception
     */
    public static function getPageData(string $slug)
    {
        /* @var Meta $meta */
        $meta = Meta::where(['url' => $slug])->get();

        /*  */
        if ($meta->count()) {
            /*  */
            if ($meta->count() > 1) {
                $meta = $meta->filter(function($meta) {
                    return $meta->class == \App\Models\Page\Page::class;
                });
            }

            $meta = $meta->first();
        } else {
            $meta = self::findModuleHomePage($slug);
        }

        /* Проверяем что страница доступна */
        if (!$meta || !$meta->is_active || $meta->publish_at > new DateTimeImmutable('now')) {
            return false;
        }

        $meta->setDefaultLocale(app()->getLocale());

        /* Поиск страницы на нужном языке */
        /* @var Model $page */
        $page = $meta->object_type::where(['uuid' => $meta->object_id])
            ->with(array_filter([
                'meta', 'translations',
                method_exists($meta->object_type, 'images') ? 'images' : null,
                method_exists($meta->object_type, 'icon') ? 'icon' : null
            ]))
            ->withDepth()
            ->firstOrFail()
            ->setDefaultLocale(app()->getLocale());

        $meta->views++;
        $meta->saveQuietly(); /* без выполнения событий */

        /* рендер компонентов */
        if (!empty($page->body)) {
            if (strpos($page->body, '<x-') !== false) {
                $page->body = self::render($page->body ?? '', []);
            }
        }

        return [
            'meta' => $meta,
            'data' => $page
        ];
    }

    /**
     * @param string $slug
     * @return mixed
     */
    private static function findModuleHomePage(string $slug)
    {
        /* prefixes страницы */
        $segments = explode('/', $slug);
        $prefixes_slug = array_filter([
            implode('/', array_slice($segments, 0, -1)),
            $slug
        ]);

        /* установленные модули */
        $object_types = Meta::select('object_type')
            ->groupBy('object_type')
            ->pluck('object_type');

        if (!$object_types) abort(404);

        $type = null;
        foreach ($object_types as $object_type) {
            if (in_array($object_type::PREFIX, $prefixes_slug)) {
                $type = $object_type;
                break;
            }
        }

        if (!$type) abort(404);

        return Meta::where(['url' => '/', 'object_type' => $type])->firstOrFail();
    }

    /**
     * @param string $string
     * @param array $data
     * @return false|string
     * @throws Exception
     */
    private static function render(string $string, array $data = [])
    {
        $__env = app(\Illuminate\View\Factory::class);
        $string = Blade::compileString($string);

        $obLevel = ob_get_level();
        ob_start();
        extract($data, EXTR_SKIP);

        try {
            eval('?' . '>' . $string);
        } catch (Exception $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw new Exception($e);
        }

        return ob_get_clean();
    }
}
