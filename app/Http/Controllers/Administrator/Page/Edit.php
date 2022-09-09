<?php

namespace App\Http\Controllers\Administrator\Page;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Finder\File;
use App\Models\Page\Page;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

/**
 * Создание и редактирование страниц
 * @package App\Http\Controllers\Administrator\Page
 */
class Edit extends Controller
{
    /**
     * @param string $locale
     * @param string|null $uuid
     * @return Application|Factory|View
     */
    public function index(string $locale, string $uuid = null)
    {
        if (empty($locale)) {
            $locale = config('translatable.locale') ?? app()->getLocale();
        }

        $parent_id = \Illuminate\Support\Facades\Request::has('parent_id')
            ? \Illuminate\Support\Facades\Request::input('parent_id')
            : null;

        return view('administrator.content.edit', [
            'uuid' => $uuid ?? Uuid::uuid4()->toString(),
            'locale' => $locale,
            'object' => $object = Page::where(['uuid' => $uuid])->with(['meta', 'images', 'translations'])->first(),
            'parent' => $parent = Page::where(['uuid' => $parent_id])->with(['meta', 'images', 'translations'])->first(),
            'nested_nodes' => (new Nested(Page::class))->optGroup($locale, $uuid),
            'templates' => $this->templates(),
            'title_create' => __('page.create'),
            'breadcrumbs_data' => [
                'locale' => $locale,
                'item' => $object,
                'object' => $object->parent ?? $parent
            ],
            'chunks_template' => $this->chunks_template($object),
            'back' => [
                'route' => route('admin.page.home', ['uuid' => $parent->uuid ?? $object->parent_id ?? null]),
                'text' => __('page.forward_back')
            ],
            'routes' => [
                'store' => 'admin.page.save',
                'edit' => 'admin.page.edit'
            ],
            'switcher_data' => [
                'uuid' => $uuid
            ]
        ]);
    }

    /**
     * Шаблоны для формирования страницы
     * Боковое меню + контент
     * @param Page|null $page
     * @return array[]
     */
    private function chunks_template(Page $page = null): array
    {
        return [
            [
                'icon' => '<i class="fas fa-cog me-1"></i>',
                'key' => 'general',
                'name' => __('global.tabs.general'),
                'template' => 'administrator.page.general',
                'data' => []
            ],
            [
                'key' => 'videos',
                'name' => __('video.title'),
                'template' => 'administrator.video.items',
                'data' => [
                    'videos' => $page->videos ?? null,
                    'object' => $page
                ]
            ],
            [
                'icon' => '<i class="fas fa-chart-line me-1"></i>',
                'key'  => __('meta.name'),
                'name' => 'SEO',
                'template' => 'administrator.meta.edit',
                'data' => [
                    'parent' => $page,
                    'object_type' => Page::class
                ],
            ],
        ];
    }

    /**
     * Доступные шаблоны для страниц
     * @return array
     */
    private function templates(): array
    {
        $templates_path = resource_path('views/web/pages');
        $templates = glob($templates_path . '/*.blade.php');

        $template_file_name = [];
        if ($templates) {
            foreach ($templates as $template) {
                $template_file_name[] = str_replace('.blade.php', '', basename($template));
            }
        }

        return $template_file_name;
    }

}
